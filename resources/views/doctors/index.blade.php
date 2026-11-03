@extends('layouts.app')
@section('title', 'Find a Doctor | CarePlus Hospital')
@section('meta_description', 'Find experienced doctors by name, department and hospital.')
@section('content')
<section class="section-wrap" style="padding:55px 0">
 <div class="container">
  <p class="eyebrow">OUR SPECIALISTS</p><h1>Find a Doctor</h1><p>Search our experienced medical professionals and choose the right specialist for your care.</p>
  <form method="GET" action="{{ route('doctors.index') }}" class="doctor-filter" style="display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:12px;margin:28px 0">
   <input name="q" value="{{ request('q') }}" placeholder="Doctor name, degree or specialty" aria-label="Search doctors">
   <select name="department"><option value="">All departments</option>@foreach($departments as $department)<option value="{{ $department->slug }}" @selected(request('department')===$department->slug)>{{ $department->name }}</option>@endforeach</select>
   <select name="hospital"><option value="">All hospitals</option>@foreach($hospitals as $hospital)<option value="{{ $hospital->slug }}" @selected(request('hospital')===$hospital->slug)>{{ $hospital->name }}</option>@endforeach</select>
   <button class="btn btn-primary" type="submit">Search</button>
  </form>
  <div class="doctor-grid">@forelse($doctors as $doctor)
   <article class="doctor-card">
    <a href="{{ route('doctors.show',$doctor->slug) }}" class="doctor-card-image">@if($doctor->image)<img src="{{ asset('storage/'.$doctor->image) }}" alt="{{ $doctor->name }}" loading="lazy">@else<div class="doctor-placeholder">{{ strtoupper(mb_substr($doctor->name,0,1)) }}</div>@endif</a>
    <div class="doctor-card-body"><h3><a href="{{ route('doctors.show',$doctor->slug) }}">{{ $doctor->name }}</a></h3><p class="doctor-designation">{{ $doctor->designation }}</p><p>{{ $doctor->degrees }}</p><p class="doctor-meta">{{ $doctor->departments->pluck('name')->join(', ') }}</p><a class="text-link" href="{{ route('doctors.show',$doctor->slug) }}">View profile →</a></div>
   </article>
  @empty <p>No doctors found. Try another search.</p> @endforelse</div>
  <div class="pagination-wrap">{{ $doctors->links() }}</div>
 </div>
</section>
@endsection
@push('styles')
<style>
.doctor-filter input,.doctor-filter select{width:100%;padding:13px 14px;border:1px solid #dce6ec;border-radius:9px;background:#fff;font:inherit}.doctor-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:22px}.doctor-card{overflow:hidden;border:1px solid #e4edf1;border-radius:14px;background:#fff;transition:transform .25s,box-shadow .25s}.doctor-card:hover{transform:translateY(-6px);box-shadow:0 15px 35px #12334d18}.doctor-card-image{display:block;height:220px;background:#edf5f7}.doctor-card-image img{width:100%;height:100%;object-fit:cover}.doctor-placeholder{height:100%;display:grid;place-items:center;font-size:64px;color:#078f9a}.doctor-card-body{padding:18px}.doctor-card h3{margin:0 0 7px;font-size:18px}.doctor-card h3 a{color:#12334d;text-decoration:none}.doctor-card p{margin:5px 0;font-size:13px;color:#667987}.doctor-designation{color:#009b9b!important;font-weight:600}.doctor-meta{min-height:35px}.text-link{color:#008f9a;font-weight:600;font-size:13px;text-decoration:none}.pagination-wrap{margin-top:30px}.pagination-wrap nav{display:flex;justify-content:center}.pagination-wrap nav a,.pagination-wrap nav span{padding:8px 12px;border:1px solid #e2eaf0;text-decoration:none;color:#12334d}.pagination-wrap [aria-current=page] span{background:#087e91;color:white}.pagination-wrap svg{width:18px}.pagination-wrap p{font-size:13px;color:#687b87}@media(max-width:900px){.doctor-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.doctor-filter{grid-template-columns:1fr 1fr!important}}@media(max-width:560px){.doctor-grid{grid-template-columns:1fr 1fr;gap:12px}.doctor-card-image{height:155px}.doctor-card-body{padding:12px}.doctor-card h3{font-size:15px}.doctor-filter{grid-template-columns:1fr!important}}
</style>
@endpush
