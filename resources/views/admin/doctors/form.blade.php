@extends('admin.doctors.layout')
@section('title', $doctor->exists ? 'Edit Doctor' : 'Add Doctor')
@section('content')
    <form class="admin-card" method="POST" enctype="multipart/form-data"
        action="{{ $doctor->exists ? route('admin.doctors.update', $doctor) : route('admin.doctors.store') }}">
        @csrf @if ($doctor->exists)
            @method('PUT')
        @endif
        <div class="admin-actions" style="justify-content:space-between">
            <div>
                <h1 style="margin:0">{{ $doctor->exists ? 'Edit Doctor' : 'Add New Doctor' }}</h1>
                <p class="muted">Complete the profile and assign departments, hospitals and chamber times.</p>
            </div><a class="btn btn-muted" href="{{ route('admin.doctors.index') }}">← Back</a>
        </div>
        <h3>Basic Information</h3>
        <div class="grid">
            <div class="field"><label>Doctor Name *</label><input name="name" required
                    value="{{ old('name', $doctor->name) }}"></div>
            <div class="field"><label>URL Slug (optional)</label><input name="slug"
                    value="{{ old('slug', $doctor->slug) }}" placeholder="auto-generated-from-name"></div>
            <div class="field"><label>Designation</label><input name="designation"
                    value="{{ old('designation', $doctor->designation) }}" placeholder="Senior Consultant"></div>
            <div class="field"><label>Degrees</label><input name="degrees" value="{{ old('degrees', $doctor->degrees) }}"
                    placeholder="MBBS, FCPS, MD"></div>
            <div class="field"><label>Specialization</label><input name="specialization"
                    value="{{ old('specialization', $doctor->specialization) }}"></div>
            <div class="field"><label>Experience</label><input name="experience"
                    value="{{ old('experience', $doctor->experience) }}" placeholder="15 years"></div>
            <div class="field"><label>Phone</label><input name="phone" value="{{ old('phone', $doctor->phone) }}"></div>
            <div class="field"><label>Email</label><input type="email" name="email"
                    value="{{ old('email', $doctor->email) }}"></div>
        </div>
        <div class="field"><label>Short Biography</label>
            <textarea name="short_bio">{{ old('short_bio', $doctor->short_bio) }}</textarea>
        </div>
        <div class="field"><label>Full Biography</label>
            <textarea name="biography" style="min-height:150px">{{ old('biography', $doctor->biography) }}</textarea>
        </div>
        <div class="field"><label>Profile Photo (JPG, PNG, WEBP; max 2 MB)</label><input type="file" name="image"
                accept="image/jpeg,image/png,image/webp">
            @if ($doctor->image)
                <p class="muted">Current: {{ $doctor->image }}</p>
            @endif
        </div>
        <h3>Department Assignment</h3>
        <div class="grid">
            @foreach ($departments as $department)
                <label class="check-row"><input type="checkbox" name="department_ids[]" value="{{ $department->id }}"
                        @checked(in_array($department->id, old('department_ids', $doctor->exists ? $doctor->departments->pluck('id')->all() : [])))> {{ $department->name }}</label>
            @endforeach
        </div>
        <h3 style="margin-top:25px">Hospital & Chamber Assignment</h3>
        <p class="muted">Select one or more hospitals. You can set one primary chamber and separate appointment numbers.
        </p>
        @foreach ($hospitals as $hospital)
            <div style="border:1px solid #e1e9ee;border-radius:10px;padding:15px;margin:12px 0">
                <label class="check-row"><input type="checkbox" name="hospital_ids[]" value="{{ $hospital->id }}"
                        class="hospital-toggle" data-hospital="{{ $hospital->id }}"
                        @checked(in_array($hospital->id, old('hospital_ids', $doctor->exists ? $doctor->hospitals->pluck('id')->all() : [])))><strong>{{ $hospital->name }}</strong></label>
                <div class="grid" style="margin-top:10px">
                    <div class="field"><label>Chamber / Room</label><input name="chamber_room[{{ $hospital->id }}]"
                            value="{{ old('chamber_room.' . $hospital->id, $doctor->hospitals->firstWhere('id', $hospital->id)?->pivot->chamber_room) }}">
                    </div>
                    <div class="field"><label>Appointment Phone</label><input
                            name="appointment_phone[{{ $hospital->id }}]"
                            value="{{ old('appointment_phone.' . $hospital->id, $doctor->hospitals->firstWhere('id', $hospital->id)?->pivot->appointment_phone) }}">
                    </div>
                </div>
                <label class="check-row"><input type="radio" name="primary_hospital_id" value="{{ $hospital->id }}"
                        @checked(
                            (string) old(
                                'primary_hospital_id',
                                $doctor->hospitals->firstWhere('id', $hospital->id)?->pivot->is_primary ? $hospital->id : '') === (string) $hospital->id)> Primary chamber</label>
                <details>
                    <summary>Weekly chamber schedule</summary>
                    <div style="overflow-x:auto;margin-top:10px">
                        <table class="schedule-grid">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Available</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day => $dayName)
                                    @php($oldSchedule = old('schedules.' . $hospital->id . '.' . $day))
                                    @php($savedSchedule = $schedules->get($hospital->id . '-' . $day))
                                    <tr>
                                        <td>{{ $dayName }}</td>
                                        <td><input type="checkbox"
                                                name="schedules[{{ $hospital->id }}][{{ $day }}][enabled]"
                                                value="1" @checked($oldSchedule ? !empty($oldSchedule['enabled']) : (bool) $savedSchedule)></td>
                                        <td><input type="time"
                                                name="schedules[{{ $hospital->id }}][{{ $day }}][start_time]"
                                                value="{{ $oldSchedule['start_time'] ?? ($savedSchedule?->start_time ? substr($savedSchedule->start_time, 0, 5) : '') }}">
                                        </td>
                                        <td><input type="time"
                                                name="schedules[{{ $hospital->id }}][{{ $day }}][end_time]"
                                                value="{{ $oldSchedule['end_time'] ?? ($savedSchedule?->end_time ? substr($savedSchedule->end_time, 0, 5) : '') }}">
                                        </td>
                                        <td><input name="schedules[{{ $hospital->id }}][{{ $day }}][note]"
                                                value="{{ $oldSchedule['note'] ?? $savedSchedule?->note }}"
                                                style="width:130px"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </details>
            </div>
        @endforeach
        <h3>Publishing</h3>
        <div class="grid">
            <div class="field"><label>Sort Order</label><input type="number" min="0" name="sort_order"
                    value="{{ old('sort_order', $doctor->sort_order ?? 0) }}"></div>
            <div><label class="check-row"><input type="checkbox" name="is_featured" value="1"
                        @checked(old('is_featured', $doctor->is_featured))> Featured Doctor</label><label class="check-row"><input
                        type="checkbox" name="is_active" value="1" @checked(old('is_active', $doctor->exists ? $doctor->is_active : true))> Published /
                    Active</label></div>
        </div>
        <div class="admin-actions" style="margin-top:25px"><button class="btn"
                type="submit">{{ $doctor->exists ? 'Update Doctor' : 'Save Doctor' }}</button><a class="btn btn-muted"
                href="{{ route('admin.doctors.index') }}">Cancel</a></div>
    </form>
@endsection
