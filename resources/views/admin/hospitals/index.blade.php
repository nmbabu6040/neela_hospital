@extends('admin.doctors.layout')
@section('title', 'Hospitals')
@section('content')
<div class="admin-card">
    <div class="admin-actions" style="justify-content:space-between;margin-bottom:20px">
        <div><h1>Hospitals</h1><p class="muted">Manage hospital branches, facilities and contact details.</p></div>
        <a class="btn" href="{{ route('admin.hospitals.create') }}">+ Add Hospital</a>
    </div>
    <div class="table-wrap"><table class="table">
        <thead><tr><th>Hospital</th><th>Location</th><th>Doctors</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($hospitals as $hospital)
            <tr>
                <td><strong>{{ $hospital->name }}</strong><br><span class="muted">{{ $hospital->code }} · {{ $hospital->slug }}</span></td>
                <td>{{ $hospital->city }}<br><span class="muted">{{ $hospital->address }}</span></td>
                <td>{{ $hospital->doctors_count }}</td>
                <td>{{ $hospital->is_active ? 'Active' : 'Inactive' }}</td>
                <td class="admin-actions">
                    <a class="btn btn-muted" href="{{ route('admin.hospitals.edit',$hospital) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.hospitals.destroy',$hospital) }}" onsubmit="return confirm('Move this hospital to trash?')">
                        @csrf @method('DELETE')<button class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No hospitals found.</td></tr>
        @endforelse
        </tbody>
    </table></div>
    <div style="margin-top:20px">{{ $hospitals->links() }}</div>
</div>
@endsection
