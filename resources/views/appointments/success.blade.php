@extends('layouts.app')
@section('title','Appointment Request Submitted')
@section('content')
<div style="max-width:720px;margin:55px auto;padding:25px;text-align:center">
 <div style="font-size:52px;color:#07865b">✓</div><h1>Appointment Request Submitted</h1>
 <p>Your request has been received. The hospital will contact you to confirm it.</p>
 <div style="display:inline-block;text-align:left;background:#f4f8fa;padding:20px 28px;border-radius:12px">
 <p><b>Booking Code:</b> {{ session('booking_code','APT-'.$appointment->id) }}</p>
 <p><b>Patient:</b> {{ $appointment->patient_name }}</p><p><b>Doctor:</b> {{ $appointment->doctor?->name }}</p>
 <p><b>Hospital:</b> {{ $appointment->hospital?->name }}</p>
 <p><b>Date:</b> {{ $appointment->appointment_date->format('d M Y') }}</p>
 <p><b>Time:</b> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
 <p><b>Status:</b> {{ ucfirst($appointment->status) }}</p></div>
 <p><a href="{{ route('appointments.create') }}">Book another appointment</a></p>
</div>
@endsection
