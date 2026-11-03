<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Department;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DoctorController extends Controller
{
    public function index(Request $request): View
    {
        $doctors = Doctor::with(['departments:id,name','hospitals:id,name'])
            ->when($request->filled('q'), fn ($q) => $q->where('name','like','%'.trim($request->q).'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->status === 'active'))
            ->orderByDesc('created_at')->paginate(15)->withQueryString();
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create(): View
    {
        return view('admin.doctors.form', ['doctor' => new Doctor(), 'departments' => Department::where('is_active',true)->orderBy('name')->get(), 'hospitals' => Hospital::where('is_active',true)->orderBy('name')->get(), 'schedules' => collect()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        DB::transaction(function () use ($request, $data) {
            $doctor = Doctor::create($this->doctorData($request, $data));
            $this->syncRelations($doctor, $data);
        });
        return redirect()->route('admin.doctors.index')->with('status', 'Doctor created successfully.');
    }

    public function edit(Doctor $doctor): View
    {
        $doctor->load(['departments:id','hospitals:id','schedules']);
        return view('admin.doctors.form', ['doctor' => $doctor, 'departments' => Department::where('is_active',true)->orderBy('name')->get(), 'hospitals' => Hospital::where('is_active',true)->orderBy('name')->get(), 'schedules' => $doctor->schedules->keyBy(fn ($s) => $s->hospital_id.'-'.$s->day_of_week)]);
    }

    public function update(Request $request, Doctor $doctor): RedirectResponse
    {
        $data = $this->validated($request, $doctor);
        DB::transaction(function () use ($request, $data, $doctor) {
            $doctor->update($this->doctorData($request, $data, $doctor));
            $this->syncRelations($doctor, $data);
        });
        return redirect()->route('admin.doctors.index')->with('status', 'Doctor updated successfully.');
    }

    public function destroy(Doctor $doctor): RedirectResponse
    {
        $doctor->delete();
        return back()->with('status', 'Doctor moved to trash.');
    }

    private function validated(Request $request, ?Doctor $doctor = null): array
    {
        return $request->validate([
            'name' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255', Rule::unique('doctors','slug')->ignore($doctor?->id)],
            'designation' => ['nullable','string','max:255'], 'degrees' => ['nullable','string','max:255'],
            'specialization' => ['nullable','string','max:255'], 'experience' => ['nullable','string','max:255'],
            'phone' => ['nullable','string','max:40'], 'email' => ['nullable','email','max:255'],
            'short_bio' => ['nullable','string','max:1000'], 'biography' => ['nullable','string'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'department_ids' => ['nullable','array'], 'department_ids.*' => ['integer','exists:departments,id'],
            'hospital_ids' => ['nullable','array'], 'hospital_ids.*' => ['integer','exists:hospitals,id'],
            'primary_hospital_id' => ['nullable','integer','exists:hospitals,id'],
            'chamber_room' => ['nullable','array'], 'appointment_phone' => ['nullable','array'],
            'schedules' => ['nullable','array'],
            'is_featured' => ['nullable','boolean'], 'is_active' => ['nullable','boolean'], 'sort_order' => ['nullable','integer','min:0'],
        ]);
    }

    private function doctorData(Request $request, array $data, ?Doctor $doctor = null): array
    {
        $fields = ['name','designation','degrees','specialization','experience','phone','email','short_bio','biography'];
        $payload = collect($data)->only($fields)->all();
        $slug = trim((string)($data['slug'] ?? '')) ?: Str::slug($data['name']);
        $base = $slug; $i = 2;
        while (Doctor::withTrashed()->where('slug',$slug)->when($doctor,fn($q)=>$q->where('id','!=',$doctor->id))->exists()) $slug = $base.'-'.$i++;
        $payload['slug'] = $slug;
        $payload['is_featured'] = $request->boolean('is_featured');
        $payload['is_active'] = $request->boolean('is_active');
        $payload['sort_order'] = (int)$request->input('sort_order', 0);
        if ($request->hasFile('image')) {
            $payload['image'] = $request->file('image')->store('doctors', 'public');
        }
        return $payload;
    }

    private function syncRelations(Doctor $doctor, array $data): void
    {
        $doctor->departments()->sync($data['department_ids'] ?? []);
        $hospitalSync = [];
        foreach (($data['hospital_ids'] ?? []) as $id) {
            $hospitalSync[$id] = [
                'is_primary' => (int)($data['primary_hospital_id'] ?? 0) === (int)$id,
                'chamber_room' => $data['chamber_room'][$id] ?? null,
                'appointment_phone' => $data['appointment_phone'][$id] ?? null,
            ];
        }
        $doctor->hospitals()->sync($hospitalSync);
        $doctor->schedules()->delete();
        foreach (($data['schedules'] ?? []) as $hospitalId => $days) {
            if (!in_array((int)$hospitalId, array_map('intval', array_keys($hospitalSync)), true)) continue;
            foreach ($days as $day => $schedule) {
                if (empty($schedule['enabled'])) continue;
                $doctor->schedules()->create([
                    'hospital_id' => (int)$hospitalId, 'day_of_week' => (int)$day,
                    'start_time' => $schedule['start_time'] ?: null, 'end_time' => $schedule['end_time'] ?: null,
                    'is_available' => true, 'note' => $schedule['note'] ?? null,
                ]);
            }
        }
    }
}
