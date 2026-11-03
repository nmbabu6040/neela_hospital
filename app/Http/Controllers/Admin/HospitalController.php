<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class HospitalController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::withCount('doctors')->orderBy('sort_order')->orderBy('name')->paginate(20);
        return view('admin.hospitals.index', compact('hospitals'));
    }

    public function create()
    {
        return view('admin.hospitals.form', ['hospital' => new Hospital()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name']);
        $data['facilities'] = $this->facilityArray($request->input('facilities'));
        if ($request->hasFile('image')) $data['image'] = $request->file('image')->store('hospitals', 'public');
        Hospital::create($data);
        return redirect()->route('admin.hospitals.index')->with('status', 'Hospital created successfully.');
    }

    public function edit(Hospital $hospital)
    {
        return view('admin.hospitals.form', compact('hospital'));
    }

    public function update(Request $request, Hospital $hospital)
    {
        $data = $this->validated($request, $hospital);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name'], $hospital->id);
        $data['facilities'] = $this->facilityArray($request->input('facilities'));
        if ($request->hasFile('image')) {
            if ($hospital->image) \Storage::disk('public')->delete($hospital->image);
            $data['image'] = $request->file('image')->store('hospitals', 'public');
        }
        $hospital->update($data);
        return redirect()->route('admin.hospitals.index')->with('status', 'Hospital updated successfully.');
    }

    public function destroy(Hospital $hospital)
    {
        $hospital->delete();
        return back()->with('status', 'Hospital moved to trash.');
    }

    private function validated(Request $request, ?Hospital $hospital = null): array
    {
        return $request->validate([
            'name' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255', Rule::unique('hospitals','slug')->ignore($hospital?->id)],
            'code' => ['nullable','string','max:30', Rule::unique('hospitals','code')->ignore($hospital?->id)],
            'phone' => ['nullable','string','max:40'],
            'email' => ['nullable','email','max:255'],
            'address' => ['nullable','string','max:255'],
            'city' => ['nullable','string','max:100'],
            'short_description' => ['nullable','string','max:1000'],
            'description' => ['nullable','string'],
            'facilities' => ['nullable','string'],
            'map_embed_url' => ['nullable','url','max:1000'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:3072'],
            'is_active' => ['nullable','boolean'],
            'sort_order' => ['nullable','integer','min:0'],
        ]) + [
            'is_active' => $request->boolean('is_active'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ];
    }

    private function facilityArray(?string $value): array
    {
        return collect(preg_split('/[\r\n,]+/', $value ?? ''))->map(fn($v) => trim($v))->filter()->values()->all();
    }

    private function uniqueSlug(string $value, ?int $ignore = null): string
    {
        $base = Str::slug($value) ?: 'hospital';
        $slug = $base; $i = 2;
        while (Hospital::withTrashed()->where('slug', $slug)->when($ignore, fn($q) => $q->where('id','!=',$ignore))->exists()) $slug = $base.'-'.$i++;
        return $slug;
    }
}
