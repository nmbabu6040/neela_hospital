@extends('admin.doctors.layout')
@section('title', 'Doctor Schedules')
@section('content')
    <div class="admin-actions" style="justify-content:space-between;margin-bottom:18px">
        <div>
            <h1 style="margin:0">Doctor Schedules</h1>
            <p class="muted">Manage visiting days and time slots.</p>
        </div><a class="btn" href="{{ route('admin.schedules.create') }}">+ Add Schedule</a>
    </div>
    <div class="admin-card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Hospital</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Availability</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->doctor?->name ?? 'Deleted doctor' }}</td>
                            <td>{{ $schedule->hospital?->name ?? 'Deleted hospital' }}</td>
                            <td>{{ ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][$schedule->day_of_week] ?? '—' }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} –
                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</td>
                            <td>{{ $schedule->is_available ? 'Available' : 'Unavailable' }}</td>
                            <td>
                                <div class="admin-actions flex gap-2 items-center">
                                    <a class="btn btn-muted" href="{{ route('admin.schedules.edit', $schedule) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}"
                                        onsubmit="return confirm('Delete this schedule?')">@csrf @method('DELETE')<button
                                            class="btn btn-danger">Delete</button></form>
                                </div>
                            </td>
                        </tr>
                    @empty<tr>
                            <td colspan="6">No schedules found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:18px">{{ $schedules->links() }}</div>
    </div>
@endsection
