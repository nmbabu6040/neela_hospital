@extends('admin.doctors.layout')

@section('title', 'Dashboard')
@section('page-heading', 'Dashboard')

@section('content')
<div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
    <div><p class="text-sm font-medium text-cyan-700">Overview</p><h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Welcome back, {{ auth()->user()->name }}!</h1><p class="mt-2 text-sm text-slate-500">Here is what is happening across your hospital website today.</p></div>
    <div class="text-sm text-slate-500">Dashboard overview</div>
</div>
@php
    $dashboardCards = [
        ['label' => 'Doctors', 'count' => $doctorCount, 'icon' => '✚', 'tone' => 'cyan'],
        ['label' => 'Departments', 'count' => $departmentCount, 'icon' => '▦', 'tone' => 'violet'],
        ['label' => 'Hospitals', 'count' => $hospitalCount, 'icon' => '⌂', 'tone' => 'emerald'],
        ['label' => 'Packages', 'count' => $packageCount, 'icon' => '▤', 'tone' => 'amber'],
        ['label' => 'News', 'count' => $newsCount, 'icon' => '▧', 'tone' => 'blue'],
        ['label' => 'Appointments', 'count' => $appointmentCount, 'icon' => '▣', 'tone' => 'rose'],
        ['label' => 'New Messages', 'count' => $newMessageCount, 'icon' => '✉', 'tone' => 'indigo'],
    ];
    $toneClasses = [
        'cyan' => 'bg-cyan-50 text-cyan-700 ring-cyan-100', 'violet' => 'bg-violet-50 text-violet-700 ring-violet-100',
        'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-100', 'amber' => 'bg-amber-50 text-amber-700 ring-amber-100',
        'blue' => 'bg-blue-50 text-blue-700 ring-blue-100', 'rose' => 'bg-rose-50 text-rose-700 ring-rose-100',
        'indigo' => 'bg-indigo-50 text-indigo-700 ring-indigo-100',
    ];
@endphp
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
    @foreach ($dashboardCards as $card)
    <section class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg">
        <div class="flex items-center justify-between"><div class="text-sm font-semibold text-slate-500">{{ $card['label'] }}</div><div class="flex h-11 w-11 items-center justify-center rounded-xl text-lg font-bold ring-1 {{ $toneClasses[$card['tone']] }}">{{ $card['icon'] }}</div></div>
        <div class="mt-5 flex items-end justify-between"><div class="text-3xl font-bold tracking-tight text-slate-900">{{ number_format((int) $card['count']) }}</div><span class="text-xs font-medium text-slate-400">Total records</span></div>
        <div class="mt-4 h-1.5 overflow-hidden rounded-full bg-slate-100"><div class="h-full w-2/3 rounded-full bg-gradient-to-r from-cyan-500 to-blue-500 transition-all duration-500 group-hover:w-full"></div></div>
    </section>
    @endforeach
</div>
<section class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between"><div><h2 class="text-lg font-bold text-slate-900">Content management</h2><p class="mt-1 text-sm text-slate-500">Use the navigation panel to manage your hospital website content.</p></div><span class="inline-flex w-fit items-center rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold text-cyan-700">Admin panel</span></div>
    @php
        $quickLinks = [
            ['label' => 'Manage Doctors', 'route' => 'admin.doctors.index'],
            ['label' => 'Manage Departments', 'route' => 'admin.departments.index'],
            ['label' => 'Manage Hospitals', 'route' => 'admin.hospitals.index'],
            ['label' => 'Manage Schedules', 'route' => 'admin.schedules.index'],
        ];
    @endphp
    <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($quickLinks as $link)
            @if (\Illuminate\Support\Facades\Route::has($link['route']))
            <a href="{{ route($link['route']) }}" class="flex min-h-12 items-center justify-between gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-cyan-300 hover:bg-cyan-50 hover:text-cyan-800">{{ $link['label'] }} <span aria-hidden="true">→</span></a>
            @endif
        @endforeach
    </div>
</section>
@endsection
