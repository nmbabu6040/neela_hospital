@extends('layouts.app')
@section('title','Book an Appointment')
@push('styles')
<style>
.ap-wrap{max-width:1050px;margin:42px auto;padding:0 18px}.ap-head{text-align:center;margin-bottom:25px}.ap-head h1{color:#123b55}
.ap-card{background:#fff;border:1px solid #e2e8ee;border-radius:16px;padding:26px;box-shadow:0 10px 30px #123b5510}
.ap-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:17px}.ap-field{display:flex;flex-direction:column;gap:7px}
.ap-field label{font-weight:600}.ap-field input,.ap-field select,.ap-field textarea{width:100%;padding:12px;border:1px solid #d5dfe7;border-radius:8px;font:inherit}
.ap-field textarea{min-height:95px}.ap-full{grid-column:1/-1}.ap-submit{background:#087f8c;color:#fff;border:0;padding:13px 22px;border-radius:8px;font-weight:700;cursor:pointer}
.ap-error{background:#fff0f0;color:#a12626;padding:12px;border-radius:8px;margin-bottom:15px}.ap-hint{font-size:12px;color:#667085}
@media(max-width:650px){.ap-grid{grid-template-columns:1fr}.ap-full{grid-column:auto}.ap-card{padding:17px}}
</style>
@endpush
@section('content')
<div class="ap-wrap">
 <div class="ap-head"><h1>Book an Appointment</h1><p>Choose a doctor, chamber and available time.</p></div>
 @if($errors->any())<div class="ap-error"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
 <form class="ap-card" method="POST" action="{{ route('appointments.store') }}">
 @csrf
 <div class="ap-grid">
  <div class="ap-field"><label>Department (optional)</label><select id="department_id" name="department_id"><option value="">Select Department</option>@foreach($departments as $d)<option value="{{ $d->id }}" @selected(old('department_id')==$d->id)>{{ $d->name }}</option>@endforeach</select></div>
  <div class="ap-field"><label>Doctor *</label><select id="doctor_id" name="doctor_id" required><option value="">Select Doctor</option>@foreach($doctors as $d)<option value="{{ $d->id }}" @selected(old('doctor_id')==$d->id)>{{ $d->name }}{{ $d->designation ? ' — '.$d->designation : '' }}</option>@endforeach</select></div>
  <div class="ap-field"><label>Hospital / Chamber *</label><select id="hospital_id" name="hospital_id" required><option value="">Select Hospital</option>@foreach($hospitals as $h)<option value="{{ $h->id }}" @selected(old('hospital_id')==$h->id)>{{ $h->name }}</option>@endforeach</select></div>
  <div class="ap-field"><label>Appointment Date *</label><input type="date" id="appointment_date" name="appointment_date" min="{{ now()->toDateString() }}" value="{{ old('appointment_date') }}" required></div>
  <div class="ap-field"><label>Available Time *</label><select id="appointment_time" name="appointment_time" required><option value="">Select doctor, hospital and date first</option></select><span class="ap-hint">30-minute slots from the doctor's schedule.</span></div>
  <div class="ap-field"><label>Patient Name *</label><input name="patient_name" value="{{ old('patient_name') }}" maxlength="150" required></div>
  <div class="ap-field"><label>Phone Number *</label><input name="patient_phone" type="tel" value="{{ old('patient_phone') }}" maxlength="30" required></div>
  <div class="ap-field"><label>Email</label><input name="patient_email" type="email" value="{{ old('patient_email') }}"></div>
  <div class="ap-field"><label>Age</label><input name="patient_age" type="number" min="0" max="120" value="{{ old('patient_age') }}"></div>
  <div class="ap-field"><label>Gender</label><select name="patient_gender"><option value="">Select</option><option value="male">Male</option><option value="female">Female</option><option value="other">Other</option></select></div>
  <div class="ap-field ap-full"><label>Problem / Message</label><textarea name="patient_message" maxlength="2000">{{ old('patient_message') }}</textarea></div>
 </div>
 <p style="margin-top:20px"><button class="ap-submit">Submit Appointment Request</button></p>
 <span class="ap-hint">Your request remains Pending until the hospital confirms it.</span>
 </form>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',()=>{
 const dep=document.getElementById('department_id'),doc=document.getElementById('doctor_id'),hos=document.getElementById('hospital_id'),date=document.getElementById('appointment_date'),time=document.getElementById('appointment_time');
 const reset=(el,label)=>el.innerHTML=`<option value="">${label}</option>`;
 dep.addEventListener('change',async()=>{
  if(!dep.value)return;
  reset(doc,'Loading doctors...');
  const r=await fetch(`/appointment-data/departments/${dep.value}/doctors`),items=await r.json();
  reset(doc,'Select Doctor');items.forEach(x=>doc.add(new Option(x.name+(x.designation?' — '+x.designation:''),x.id)));
  reset(hos,'Select Hospital');reset(time,'Choose doctor, hospital and date first');
 });
 doc.addEventListener('change',async()=>{
  reset(hos,'Loading hospitals...');
  if(!doc.value){reset(hos,'Select Hospital');return;}
  const r=await fetch(`/appointment-data/doctors/${doc.value}/hospitals`),items=await r.json();
  reset(hos,'Select Hospital');items.forEach(x=>hos.add(new Option(x.name,x.id)));loadSlots();
 });
 async function loadSlots(){
  if(!doc.value||!hos.value||!date.value){reset(time,'Choose doctor, hospital and date first');return;}
  reset(time,'Loading available times...');
  const p=new URLSearchParams({doctor_id:doc.value,hospital_id:hos.value,date:date.value});
  const r=await fetch(`/appointment-slots?${p}`),items=await r.json();reset(time,items.length?'Select Available Time':'No slots available');
  items.forEach(x=>time.add(new Option(x.label,x.value)));
 }
 hos.addEventListener('change',loadSlots);date.addEventListener('change',loadSlots);
});
</script>
@endpush
