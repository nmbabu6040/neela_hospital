@extends('admin.doctors.layout') @section('title','Add Doctor Schedule')
@section('content')<h1>Add Doctor Schedule</h1><form method="POST" action="{{ route('admin.schedules.store') }}">@csrf @include('admin.schedules._form')</form>@endsection
