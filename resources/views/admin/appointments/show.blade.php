@extends('admin.doctors.layout')
@section('title','Appointment Details')
@section('content')
<div class="container-fluid py-3">
<div class="d-flex justify-content-between mb-3"><h1 class="h3">Appointment Details</h1><a class="btn btn-outline-secondary" href="{{ route('admin.appointments.index') }}">Back</a></div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card mb-3"><div class="card-body row">
<div class="col-md-6"><h5>Patient</h5><p><b>Name:</b> {{ $appointment->patient_name }}</p><p><b>Phone:</b> {{ $appointment->patient_phone }}</p><p><b>Email:</b> {{ $appointment->patient_email ?: '—' }}</p><p><b>Age:</b> {{ $appointment->patient_age ?: '—' }}</p><p><b>Gender:</b> {{ $appointment->patient_gender ?: '—' }}</p><p><b>Message:</b> {{ $appointment->patient_message ?: '—' }}</p></div>
<div class="col-md-6"><h5>Appointment</h5><p><b>Doctor:</b> {{ $appointment->doctor?->name ?? '—' }}</p><p><b>Department:</b> {{ $appointment->department?->name ?? '—' }}</p><p><b>Hospital:</b> {{ $appointment->hospital?->name ?? '—' }}</p><p><b>Date:</b> {{ $appointment->appointment_date->format('d M Y') }}</p><p><b>Time:</b> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p></div>
</div></div>
<div class="card"><div class="card-body"><h5>Update status</h5>
<form method="POST" action="{{ route('admin.appointments.update',$appointment) }}">@csrf @method('PATCH')
<div class="mb-3"><label class="form-label">Status</label><select name="status" class="form-select">@foreach(['pending','confirmed','cancelled','completed'] as $s)<option value="{{ $s }}" @selected($appointment->status===$s)>{{ ucfirst($s) }}</option>@endforeach</select></div>
<div class="mb-3"><label class="form-label">Admin note</label><textarea name="admin_note" class="form-control" rows="3">{{ old('admin_note',$appointment->admin_note) }}</textarea></div><button class="btn btn-primary">Save Changes</button></form>
<form method="POST" action="{{ route('admin.appointments.destroy',$appointment) }}" class="mt-3" onsubmit="return confirm('Delete this appointment?')">@csrf @method('DELETE')<button class="btn btn-outline-danger">Delete Appointment</button></form>
</div></div></div>
@endsection
