<?php

namespace App\Http\Controllers;

use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::where('is_active', true)->withCount('doctors')->orderBy('sort_order')->orderBy('name')->paginate(20);
        return view('departments.index', compact('departments'));
    }

    public function show(string $slug)
    {
        $department = Department::where('is_active', true)->where('slug', $slug)
            ->with(['doctors' => fn($q) => $q->where('is_active', true)->with('hospitals')->orderBy('sort_order')->orderBy('name')])->firstOrFail();
        $doctors = $department->doctors()->where('is_active', true)->paginate(12);
        return view('departments.show', compact('department', 'doctors'));
    }
}
