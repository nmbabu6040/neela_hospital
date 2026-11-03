<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CarePlus Hospital</title>
    <style>body{font-family:Arial,sans-serif;margin:0;color:#183044;background:#f4f8fb}header{background:#075985;color:white;padding:22px 5%;display:flex;justify-content:space-between;align-items:center}main{max-width:1100px;margin:45px auto;padding:0 20px}.hero{background:white;border-radius:16px;padding:45px;box-shadow:0 8px 30px #0f172a10}h1{font-size:clamp(30px,5vw,48px);margin:0 0 12px}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:16px;margin-top:20px}.card{background:white;border-radius:12px;padding:20px}.muted{color:#64748b}a{color:#0369a1}</style>
</head>
<body>
<header><strong>CarePlus Hospital</strong><a style="color:white" href="{{ route('admin.login') }}">Admin Login</a></header>
<main>
    <section class="hero"><h1>Quality Healthcare, Closer to You</h1><p class="muted">Welcome to CarePlus Hospital. Our website is being prepared. Explore our hospitals, departments and healthcare professionals.</p></section>
    <h2>Our Hospitals</h2><div class="grid">@forelse($hospitals as $hospital)<article class="card"><h3>{{ $hospital->name }}</h3><p class="muted">{{ $hospital->address }}</p></article>@empty<p>No hospitals published yet.</p>@endforelse</div>
    <h2>Departments</h2><div class="grid">@forelse($departments as $department)<article class="card"><h3>{{ $department->name }}</h3><p class="muted">{{ $department->short_description }}</p></article>@empty<p>No departments published yet.</p>@endforelse</div>
    <h2>Featured Doctors</h2><div class="grid">@forelse($doctors as $doctor)<article class="card"><h3>{{ $doctor->name }}</h3><p class="muted">{{ $doctor->designation }}</p></article>@empty<p>No doctors published yet.</p>@endforelse</div>
    <h2>Health Packages</h2><div class="grid">@forelse($packages as $package)<article class="card"><h3>{{ $package->name }}</h3><p>{{ number_format((float) $package->price, 2) }}</p></article>@empty<p>No packages published yet.</p>@endforelse</div>
    <h2>Latest News</h2><div class="grid">@forelse($news as $item)<article class="card"><h3>{{ $item->title }}</h3><p class="muted">{{ $item->excerpt }}</p></article>@empty<p>No news published yet.</p>@endforelse</div>
</main>
</body></html>
