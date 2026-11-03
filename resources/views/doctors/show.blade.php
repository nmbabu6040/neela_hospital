@extends('layouts.app')
@section('title', $doctor->name.' | CarePlus Hospital')
@section('meta_description', $doctor->short_bio ?: $doctor->name.' - '.$doctor->designation)
@section('content')
<section class="section-wrap" style="padding:55px 0"><div class="container">
 <p class="eyebrow">DOCTOR PROFILE</p>
 <div class="doctor-profile">
  <div class="profile-photo">@if($doctor->image)<img src="{{ asset('storage/'.$doctor->image) }}" alt="{{ $doctor->name }}">@else<div class="doctor-placeholder">{{ strtoupper(mb_substr($doctor->name,0,1)) }}</div>@endif</div>
  <div><h1>{{ $doctor->name }}</h1><p class="doctor-designation">{{ $doctor->designation }}</p><p><strong>Degrees:</strong> {{ $doctor->degrees ?: 'Not provided' }}</p><p><strong>Specialization:</strong> {{ $doctor->specialization ?: 'Not provided' }}</p><p><strong>Experience:</strong> {{ $doctor->experience ?: 'Not provided' }}</p><p>{{ $doctor->short_bio }}</p><a class="btn btn-primary" href="{{ $doctor->phone ? 'tel:'.preg_replace('/[^0-9+]/', '', $doctor->phone) : '#appointment-coming-soon' }}">Book Appointment</a><span id="appointment-coming-soon" class="muted">Online booking will be available in Phase 5. You can call the doctor's listed number.</span></div>
 </div>
 <div class="profile-columns"><section class="profile-panel"><h2>About the Doctor</h2><div>{{ $doctor->biography ?: $doctor->short_bio ?: 'Profile information will be updated soon.' }}</div></section><section class="profile-panel"><h2>Chamber & Schedule</h2>
 @forelse($doctor->hospitals as $hospital)<div class="chamber"><h3>{{ $hospital->name }} @if($hospital->pivot->is_primary)<small>Primary Chamber</small>@endif</h3><p>{{ $hospital->address }}</p><p><strong>Room:</strong> {{ $hospital->pivot->chamber_room ?: 'Contact hospital' }}</p><p><strong>Appointment:</strong> {{ $hospital->pivot->appointment_phone ?: $hospital->phone ?: 'Contact hospital' }}</p>
 <ul>@foreach($doctor->schedules->where('hospital_id',$hospital->id) as $schedule)<li>{{ ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'][$schedule->day_of_week] }}: {{ $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') : 'Time not set' }} - {{ $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') : 'Time not set' }}</li>@endforeach</ul></div>@empty<p>Chamber information is not available.</p>@endforelse
 </section></div>
 <section class="profile-panel" style="margin-top:24px"><h2>Departments</h2><p>{{ $doctor->departments->pluck('name')->join(' · ') ?: 'Not assigned' }}</p></section>
 </div></section>
@endsection
@push('styles')<style>.doctor-profile{display:grid;grid-template-columns:300px 1fr;gap:34px;align-items:start;background:#fff;border:1px solid #e5edf1;border-radius:16px;padding:26px;margin-top:20px}.profile-photo{height:330px;background:#edf5f7;border-radius:12px;overflow:hidden}.profile-photo img{width:100%;height:100%;object-fit:cover}.doctor-profile h1{margin:5px 0 10px}.doctor-profile p{color:#617583;line-height:1.7}.profile-columns{display:grid;grid-template-columns:1fr 1fr;gap:22px;margin-top:24px}.profile-panel{background:#fff;border:1px solid #e5edf1;border-radius:14px;padding:24px}.profile-panel h2{font-size:21px;color:#12334d}.chamber{padding:12px 0;border-bottom:1px solid #e8eef1}.chamber h3{font-size:16px}.chamber small{font-size:11px;color:#008d98}.chamber p,.chamber li{font-size:13px;color:#637782;line-height:1.7}@media(max-width:700px){.doctor-profile,.profile-columns{grid-template-columns:1fr}.profile-photo{height:260px}}
</style>@endpush
