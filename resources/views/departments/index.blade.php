@extends('layouts.app')
@section('title','Departments | CarePlus Hospital')
@section('content')
@include('partials.content-cards-style')
<section class="container" style="padding:55px 0">
    <p class="eyebrow">OUR SPECIALTIES</p><h1>Medical Departments</h1>
    <p>Explore our departments and specialist medical services.</p>
    <div class="content-grid">
        @forelse($departments as $department)
            <article class="content-card">
                @if($department->image)<img src="{{ asset('storage/'.$department->image) }}" alt="{{ $department->name }}" loading="lazy">@endif
                <div class="content-card-body"><h2><a href="{{ route('departments.show',$department->slug) }}">{{ $department->name }}</a></h2>
                <p>{{ $department->short_description }}</p><small>{{ $department->doctors_count }} doctors</small>
                <p><a href="{{ route('departments.show',$department->slug) }}">Explore department →</a></p></div>
            </article>
        @empty <p>No departments published yet.</p> @endforelse
    </div>
    {{ $departments->links() }}
</section>
@endsection
