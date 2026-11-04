<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard') - CarePlus Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800 antialiased">
<div class="min-h-screen">
    <div id="admin-sidebar-backdrop" class="fixed inset-0 z-30 hidden bg-slate-950/50 lg:hidden"></div>
    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full flex-col bg-slate-950 text-slate-300 transition-transform duration-300 lg:translate-x-0">
        <div class="flex h-20 items-center gap-3 border-b border-white/10 px-6">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-500 text-lg font-bold text-white">C+</div>
            <div><div class="text-lg font-bold tracking-wide text-white">CarePlus</div><div class="text-xs font-medium uppercase tracking-[0.18em] text-slate-400">Administration</div></div>
        </div>
        <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6">
            <div class="mb-3 px-3 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">Overview</div>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-cyan-500/15 text-cyan-300 ring-1 ring-inset ring-cyan-400/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"><span class="w-5 text-center">⌂</span> Dashboard</a>
            <div class="mb-3 mt-8 px-3 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">Management</div>
            @php
                $adminNavItems = [
                    ['label' => 'Doctors', 'route' => 'admin.doctors.index', 'active' => 'admin.doctors.*', 'icon' => '✚'],
                    ['label' => 'Departments', 'route' => 'admin.departments.index', 'active' => 'admin.departments.*', 'icon' => '▦'],
                    ['label' => 'Hospitals', 'route' => 'admin.hospitals.index', 'active' => 'admin.hospitals.*', 'icon' => '⌂'],
                    ['label' => 'Schedules', 'route' => 'admin.schedules.index', 'active' => 'admin.schedules.*', 'icon' => '◷'],
                    ['label' => 'Appointments', 'route' => 'admin.appointments.index', 'active' => 'admin.appointments.*', 'icon' => '▣'],
                ];
            @endphp
            @foreach ($adminNavItems as $item)
                @if (\Illuminate\Support\Facades\Route::has($item['route']))
                    <a href="{{ route($item['route']) }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs($item['active']) ? 'bg-cyan-500/15 text-cyan-300 ring-1 ring-inset ring-cyan-400/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"><span class="w-5 text-center">{{ $item['icon'] }}</span>{{ $item['label'] }}</a>
                @endif
            @endforeach
            <div class="mb-3 mt-8 px-3 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">Website</div>
            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-300 transition hover:bg-white/5 hover:text-white"><span class="w-5 text-center">↗</span> View Website</a>
        </nav>
        <div class="border-t border-white/10 p-4">
            <div class="mb-3 flex items-center gap-3 rounded-xl bg-white/5 p-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-cyan-500/20 text-sm font-bold text-cyan-300">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                <div class="min-w-0 flex-1"><div class="truncate text-sm font-semibold text-white">{{ auth()->user()->name ?? 'Administrator' }}</div><div class="text-xs text-slate-400">Administrator</div></div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">@csrf
                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl border border-white/10 px-4 py-2.5 text-sm font-semibold text-slate-300 transition hover:border-rose-400/30 hover:bg-rose-500/10 hover:text-rose-300">⇥ Sign out</button>
            </form>
        </div>
    </aside>
    <div class="min-h-screen lg:pl-72">
        <header class="sticky top-0 z-20 flex h-20 items-center justify-between border-b border-slate-200 bg-white/90 px-4 shadow-sm backdrop-blur sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <button id="admin-sidebar-toggle" type="button" aria-label="Toggle navigation" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-xl text-slate-600 transition hover:bg-slate-100 lg:hidden">☰</button>
                <div><div class="text-xs font-medium uppercase tracking-[0.16em] text-slate-400">CarePlus Hospital</div><div class="text-lg font-bold text-slate-900">@yield('page-heading', 'Admin Dashboard')</div></div>
            </div>
            <div class="hidden items-center gap-3 sm:flex"><span class="rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">System Online</span><span class="text-sm font-medium text-slate-500">{{ now()->format('d M Y') }}</span></div>
        </header>
        <main class="mx-auto w-full max-w-[1600px] px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            @if (session('success'))<div class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"><span class="font-bold">✓</span><span>{{ session('success') }}</span></div>@endif
            @if (session('error'))<div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">{{ session('error') }}</div>@endif
            @yield('content')
        </main>
    </div>
</div>
<script>
(() => {
    const sidebar = document.getElementById('admin-sidebar');
    const toggle = document.getElementById('admin-sidebar-toggle');
    const backdrop = document.getElementById('admin-sidebar-backdrop');
    const open = () => { sidebar?.classList.remove('-translate-x-full'); backdrop?.classList.remove('hidden'); };
    const close = () => { sidebar?.classList.add('-translate-x-full'); backdrop?.classList.add('hidden'); };
    toggle?.addEventListener('click', () => sidebar?.classList.contains('-translate-x-full') ? open() : close());
    backdrop?.addEventListener('click', close);
    window.addEventListener('resize', () => { if (window.innerWidth >= 1024) { sidebar?.classList.remove('-translate-x-full'); backdrop?.classList.add('hidden'); } else { sidebar?.classList.add('-translate-x-full'); } });
})();
</script>
</body>
</html>
