<?php

namespace App\Http\Controllers;

use App\Models\Hospital;

class HospitalController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::where('is_active', true)->withCount('doctors')->orderBy('sort_order')->orderBy('name')->paginate(12);
        return view('hospitals.index', compact('hospitals'));
    }

    public function show(string $slug)
    {
        $hospital = Hospital::where('is_active', true)->where('slug', $slug)
            ->with(['doctors' => fn($q) => $q->where('is_active', true)->with('departments')->orderBy('sort_order')->orderBy('name')])->firstOrFail();
        $doctors = $hospital->doctors()->where('is_active', true)->paginate(12);
        return view('hospitals.show', compact('hospital', 'doctors'));
    }
}
