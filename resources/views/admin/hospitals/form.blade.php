@extends('admin.doctors.layout')
@section('title', $hospital->exists ? 'Edit Hospital' : 'Add Hospital')
@section('content')
<div class="admin-card">
    <h1>{{ $hospital->exists ? 'Edit Hospital' : 'Add Hospital' }}</h1>
    <form method="POST" enctype="multipart/form-data" action="{{ $hospital->exists ? route('admin.hospitals.update',$hospital) : route('admin.hospitals.store') }}">
        @csrf
        @if($hospital->exists) @method('PUT') @endif
        <div class="grid">
            <div class="field"><label>Hospital Name *</label><input name="name" value="{{ old('name',$hospital->name) }}" required></div>
            <div class="field"><label>Slug (optional)</label><input name="slug" value="{{ old('slug',$hospital->slug) }}" placeholder="auto-generated-from-name"></div>
            <div class="field"><label>Branch Code</label><input name="code" value="{{ old('code',$hospital->code) }}"></div>
            <div class="field"><label>Phone</label><input name="phone" value="{{ old('phone',$hospital->phone) }}"></div>
            <div class="field"><label>Email</label><input type="email" name="email" value="{{ old('email',$hospital->email) }}"></div>
            <div class="field"><label>City</label><input name="city" value="{{ old('city',$hospital->city) }}"></div>
        </div>
        <div class="field"><label>Address</label><input name="address" value="{{ old('address',$hospital->address) }}"></div>
        <div class="field"><label>Short Description</label><textarea name="short_description">{{ old('short_description',$hospital->short_description) }}</textarea></div>
        <div class="field"><label>Overview / Description</label><textarea name="description" rows="6">{{ old('description',$hospital->description) }}</textarea></div>
        <div class="field"><label>Facilities (one per line or comma separated)</label><textarea name="facilities" rows="5">{{ old('facilities', implode("\n",$hospital->facilities ?? [])) }}</textarea></div>
        <div class="field"><label>Google Maps Embed URL</label><input name="map_embed_url" value="{{ old('map_embed_url',$hospital->map_embed_url) }}" placeholder="https://www.google.com/maps/embed?..."></div>
        <div class="field"><label>Hospital Image</label><input type="file" name="image" accept="image/*">@if($hospital->image)<p><img src="{{ asset('storage/'.$hospital->image) }}" width="160" loading="lazy"></p>@endif</div>
        <div class="field"><label>Sort Order</label><input type="number" min="0" name="sort_order" value="{{ old('sort_order',$hospital->sort_order ?? 0) }}"></div>
        <label class="check-row"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$hospital->is_active ?? true))> Active</label>
        <div class="admin-actions" style="margin-top:20px"><button class="btn">Save Hospital</button><a class="btn btn-muted" href="{{ route('admin.hospitals.index') }}">Cancel</a></div>
    </form>
</div>
@endsection
