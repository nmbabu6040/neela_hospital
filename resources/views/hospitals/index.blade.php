@extends('layouts.app')
@section('title','Our Hospitals | CarePlus Hospital')
@section('content')
@include('partials.content-cards-style')
<section class="container" style="padding:55px 0">
    <p class="eyebrow">OUR NETWORK</p><h1>Hospitals & Locations</h1>
    <p>Find a hospital, explore its facilities and meet its doctors.</p>
    <div class="content-grid">
        @forelse($hospitals as $hospital)
            <article class="content-card">
                @if($hospital->image)<img src="{{ asset('storage/'.$hospital->image) }}" alt="{{ $hospital->name }}" loading="lazy">@endif
                <div class="content-card-body"><h2><a href="{{ route('hospitals.show',$hospital->slug) }}">{{ $hospital->name }}</a></h2>
                <p>{{ $hospital->address }} {{ $hospital->city }}</p><small>{{ $hospital->doctors_count }} doctors</small>
                <p><a href="{{ route('hospitals.show',$hospital->slug) }}">Explore hospital →</a></p></div>
            </article>
        @empty <p>No hospitals published yet.</p> @endforelse
    </div>
    {{ $hospitals->links() }}
</section>
@endsection
