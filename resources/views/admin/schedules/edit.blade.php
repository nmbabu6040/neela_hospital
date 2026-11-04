@extends('admin.doctors.layout') @section('title','Edit Doctor Schedule')
@section('content')<h1>Edit Doctor Schedule</h1><form method="POST" action="{{ route('admin.schedules.update',$schedule) }}">@csrf @method('PUT') @include('admin.schedules._form')</form>@endsection
