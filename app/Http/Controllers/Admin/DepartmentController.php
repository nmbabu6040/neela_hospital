<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('doctors')->orderBy('sort_order')->orderBy('name')->paginate(20);
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.form', ['department' => new Department()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('departments', 'public');
        }
        Department::create($data);
        return redirect()->route('admin.departments.index')->with('status', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        return view('admin.departments.form', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $data = $this->validated($request, $department);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name'], $department->id);
        if ($request->hasFile('image')) {
            if ($department->image) \Storage::disk('public')->delete($department->image);
            $data['image'] = $request->file('image')->store('departments', 'public');
        }
        $department->update($data);
        return redirect()->route('admin.departments.index')->with('status', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return back()->with('status', 'Department moved to trash.');
    }

    private function validated(Request $request, ?Department $department = null): array
    {
        return $request->validate([
            'name' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255', Rule::unique('departments','slug')->ignore($department?->id)],
            'short_description' => ['nullable','string','max:1000'],
            'description' => ['nullable','string'],
            'services' => ['nullable','string'],
            'icon' => ['nullable','string','max:100'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:3072'],
            'is_featured' => ['nullable','boolean'],
            'is_active' => ['nullable','boolean'],
            'sort_order' => ['nullable','integer','min:0'],
        ]) + [
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ];
    }

    private function uniqueSlug(string $value, ?int $ignore = null): string
    {
        $base = Str::slug($value) ?: 'department';
        $slug = $base; $i = 2;
        while (Department::withTrashed()->where('slug', $slug)->when($ignore, fn($q) => $q->where('id','!=',$ignore))->exists()) $slug = $base.'-'.$i++;
        return $slug;
    }
}
