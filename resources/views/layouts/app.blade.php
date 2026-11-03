<!doctype html>
<html lang="{{ session('locale', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', \App\Models\SiteSetting::get('site_name', 'CarePlus Hospital'))</title>
    <meta name="description" content="@yield('meta_description', 'Quality healthcare, experienced doctors and modern diagnostic services.')">
    <meta name="robots" content="index,follow">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    @stack('styles')
</head>
<body>
    @include('partials.header')
    <main>@yield('content')</main>
    @include('partials.footer')
    <script src="{{ asset('js/site.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
