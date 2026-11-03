@extends('layouts.app')
@section('title', $hospital->name.' | CarePlus Hospital')
@section('meta_description', $hospital->short_description)
@section('content')
@include('partials.content-cards-style')
<section class="container" style="padding:50px 0">
    <p class="eyebrow">HOSPITAL</p><h1>{{ $hospital->name }}</h1>
    @if($hospital->image)<img src="{{ asset('storage/'.$hospital->image) }}" alt="{{ $hospital->name }}" style="width:100%;max-height:420px;object-fit:cover;border-radius:16px" loading="lazy">@endif
    <p>{{ $hospital->short_description }}</p>
    <p><strong>Address:</strong> {{ $hospital->address }} {{ $hospital->city }}</p>
    @if($hospital->phone)<p><strong>Phone:</strong> {{ $hospital->phone }}</p>@endif
    @if($hospital->email)<p><strong>Email:</strong> {{ $hospital->email }}</p>@endif
    <div style="max-width:900px;line-height:1.8">{!! nl2br(e($hospital->description)) !!}</div>
    @if($hospital->facilities)<h2>Facilities</h2><ul>@foreach($hospital->facilities as $facility)<li>{{ $facility }}</li>@endforeach</ul>@endif
    @if($hospital->map_embed_url)<h2>Find us on map</h2><iframe src="{{ $hospital->map_embed_url }}" width="100%" height="350" style="border:0;border-radius:14px" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>@endif
    <h2>Doctors at this Hospital</h2>
    <div class="content-grid">
        @forelse($doctors as $doctor)
        <article class="content-card">
            @if($doctor->image)<img src="{{ asset('storage/'.$doctor->image) }}" alt="{{ $doctor->name }}" loading="lazy">@endif
            <div class="content-card-body"><h3><a href="{{ route('doctors.show',$doctor->slug) }}">{{ $doctor->name }}</a></h3><p>{{ $doctor->designation }}</p><small>{{ $doctor->degrees }}</small></div>
        </article>
        @empty <p>No doctors are currently listed at this hospital.</p> @endforelse
    </div>
    {{ $doctors->links() }}
</section>
@endsection
