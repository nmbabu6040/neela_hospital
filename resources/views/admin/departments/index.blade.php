@extends('admin.doctors.layout')
@section('title', 'Departments')
@section('content')
<div class="admin-card">
    <div class="admin-actions" style="justify-content:space-between;margin-bottom:20px">
        <div><h1>Departments</h1><p class="muted">Manage hospital departments and specialties.</p></div>
        <a class="btn" href="{{ route('admin.departments.create') }}">+ Add Department</a>
    </div>
    <div class="table-wrap"><table class="table">
        <thead><tr><th>Name</th><th>Doctors</th><th>Status</th><th>Featured</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($departments as $department)
            <tr>
                <td><strong>{{ $department->name }}</strong><br><span class="muted">{{ $department->slug }}</span></td>
                <td>{{ $department->doctors_count }}</td>
                <td>{{ $department->is_active ? 'Active' : 'Inactive' }}</td>
                <td>{{ $department->is_featured ? 'Yes' : 'No' }}</td>
                <td class="admin-actions">
                    <a class="btn btn-muted" href="{{ route('admin.departments.edit',$department) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.departments.destroy',$department) }}" onsubmit="return confirm('Move this department to trash?')">
                        @csrf @method('DELETE')<button class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No departments found.</td></tr>
        @endforelse
        </tbody>
    </table></div>
    <div style="margin-top:20px">{{ $departments->links() }}</div>
</div>
@endsection
