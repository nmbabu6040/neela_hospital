@extends('layouts.app')
@section('title', $department->name.' | CarePlus Hospital')
@section('meta_description', $department->short_description)
@section('content')
@include('partials.content-cards-style')
<section class="container" style="padding:50px 0">
    <p class="eyebrow">DEPARTMENT</p><h1>{{ $department->name }}</h1>
    @if($department->image)<img src="{{ asset('storage/'.$department->image) }}" alt="{{ $department->name }}" style="width:100%;max-height:380px;object-fit:cover;border-radius:16px" loading="lazy">@endif
    <p>{{ $department->short_description }}</p>
    <div style="max-width:900px;line-height:1.8">{!! nl2br(e($department->description)) !!}</div>
    @if($department->services)<h2>Services</h2><ul>@foreach(preg_split('/[\r\n]+/', $department->services) as $service) @if(trim($service))<li>{{ trim($service) }}</li>@endif @endforeach</ul>@endif
    <h2>Our Specialists</h2>
    <div class="content-grid">
        @forelse($doctors as $doctor)
        <article class="content-card">
            @if($doctor->image)<img src="{{ asset('storage/'.$doctor->image) }}" alt="{{ $doctor->name }}" loading="lazy">@endif
            <div class="content-card-body"><h3><a href="{{ route('doctors.show',$doctor->slug) }}">{{ $doctor->name }}</a></h3><p>{{ $doctor->designation }}</p><small>{{ $doctor->degrees }}</small></div>
        </article>
        @empty <p>No doctors are currently listed in this department.</p> @endforelse
    </div>
    {{ $doctors->links() }}
</section>
@endsection
