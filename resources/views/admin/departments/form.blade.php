@extends('admin.doctors.layout')
@section('title', $department->exists ? 'Edit Department' : 'Add Department')
@section('content')
<div class="admin-card">
    <h1>{{ $department->exists ? 'Edit Department' : 'Add Department' }}</h1>
    <form method="POST" enctype="multipart/form-data" action="{{ $department->exists ? route('admin.departments.update',$department) : route('admin.departments.store') }}">
        @csrf
        @if($department->exists) @method('PUT') @endif
        <div class="grid">
            <div class="field"><label>Department Name *</label><input name="name" value="{{ old('name',$department->name) }}" required></div>
            <div class="field"><label>Slug (optional)</label><input name="slug" value="{{ old('slug',$department->slug) }}" placeholder="auto-generated-from-name"></div>
            <div class="field"><label>Icon (CSS class or emoji)</label><input name="icon" value="{{ old('icon',$department->icon) }}" placeholder="fa-heartbeat"></div>
            <div class="field"><label>Sort Order</label><input type="number" min="0" name="sort_order" value="{{ old('sort_order',$department->sort_order ?? 0) }}"></div>
        </div>
        <div class="field"><label>Short Description</label><textarea name="short_description">{{ old('short_description',$department->short_description) }}</textarea></div>
        <div class="field"><label>Overview / Description</label><textarea name="description" rows="6">{{ old('description',$department->description) }}</textarea></div>
        <div class="field"><label>Services (one per line)</label><textarea name="services" rows="5">{{ old('services',$department->services) }}</textarea></div>
        <div class="field"><label>Department Image</label><input type="file" name="image" accept="image/*">@if($department->image)<p><img src="{{ asset('storage/'.$department->image) }}" width="130" loading="lazy"></p>@endif</div>
        <label class="check-row"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$department->is_active ?? true))> Active</label>
        <label class="check-row"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured',$department->is_featured ?? false))> Featured on homepage</label>
        <div class="admin-actions" style="margin-top:20px"><button class="btn">Save Department</button><a class="btn btn-muted" href="{{ route('admin.departments.index') }}">Cancel</a></div>
    </form>
</div>
@endsection
