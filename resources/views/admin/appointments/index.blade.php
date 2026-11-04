@extends('admin.doctors.layout')
@section('title','Appointments')
@section('content')
<div class="container-fluid py-3">
<h1 class="h3 mb-3">Appointments</h1>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<form method="GET" class="row g-2 mb-3">
 <div class="col-md-5"><input class="form-control" name="q" value="{{ request('q') }}" placeholder="Patient name or phone"></div>
 <div class="col-md-3"><select class="form-select" name="status"><option value="">All statuses</option>@foreach(['pending','confirmed','cancelled','completed'] as $s)<option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>@endforeach</select></div>
 <div class="col-md-2"><button class="btn btn-primary">Filter</button></div>
</form>
<div class="table-responsive"><table class="table table-bordered table-striped align-middle">
<thead><tr><th>Patient</th><th>Phone</th><th>Doctor</th><th>Hospital</th><th>Date / Time</th><th>Status</th><th></th></tr></thead><tbody>
@forelse($appointments as $a)<tr><td>{{ $a->patient_name }}</td><td>{{ $a->patient_phone }}</td><td>{{ $a->doctor?->name ?? '—' }}</td><td>{{ $a->hospital?->name ?? '—' }}</td><td>{{ $a->appointment_date->format('d M Y') }}<br>{{ \Carbon\Carbon::parse($a->appointment_time)->format('h:i A') }}</td><td>{{ ucfirst($a->status) }}</td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.appointments.show',$a) }}">View</a></td></tr>
@empty<tr><td colspan="7" class="text-center">No appointments found.</td></tr>@endforelse
</tbody></table></div>{{ $appointments->links() }}
</div>
@endsection
