<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Department;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function index(Request $request): View
    {
        $doctors = Doctor::query()->where('is_active', true)
            ->with(['departments:id,name,slug', 'hospitals:id,name,slug'])
            ->when($request->filled('q'), fn ($q) => $q->where(function ($query) use ($request) {
                $term = '%' . trim($request->q) . '%';
                $query->where('name', 'like', $term)->orWhere('designation', 'like', $term)
                    ->orWhere('degrees', 'like', $term)->orWhere('specialization', 'like', $term);
            }))
            ->when($request->filled('department'), fn ($q) => $q->whereHas('departments', fn ($d) => $d->where('slug', $request->department)))
            ->when($request->filled('hospital'), fn ($q) => $q->whereHas('hospitals', fn ($h) => $h->where('slug', $request->hospital)))
            ->orderByDesc('is_featured')->orderBy('sort_order')->orderBy('name')
            ->paginate(20)->withQueryString();

        return view('doctors.index', [
            'doctors' => $doctors,
            'departments' => Department::where('is_active', true)->orderBy('name')->get(['id','name','slug']),
            'hospitals' => Hospital::where('is_active', true)->orderBy('name')->get(['id','name','slug']),
        ]);
    }

    public function show(string $slug): View
    {
        $doctor = Doctor::where('slug', $slug)->where('is_active', true)
            ->with(['departments', 'hospitals', 'schedules' => fn ($q) => $q->orderBy('day_of_week')->orderBy('start_time')])->firstOrFail();
        return view('doctors.show', compact('doctor'));
    }
}
