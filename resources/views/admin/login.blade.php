<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Admin Login</title><style>*{box-sizing:border-box}body{font-family:Arial,sans-serif;background:#eff6fb;min-height:100vh;display:grid;place-items:center;margin:0}.panel{width:min(420px,92vw);background:#fff;padding:32px;border-radius:16px;box-shadow:0 12px 40px #0f172a14}h1{margin-top:0;color:#0c4a6e}label{display:block;margin:16px 0 6px}input{width:100%;padding:12px;border:1px solid #cbd5e1;border-radius:8px}button{width:100%;padding:13px;border:0;border-radius:8px;background:#0369a1;color:white;font-weight:bold;margin-top:20px;cursor:pointer}.error{color:#b91c1c;font-size:14px}.status{color:#047857}</style></head><body>
<div class="panel"><h1>Admin Login</h1><p>Sign in to manage the hospital website.</p>
@if(session('status'))<p class="status">{{ session('status') }}</p>@endif
@if($errors->any())<div class="error">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
<form method="POST" action="{{ route('admin.login.submit') }}">@csrf<label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus><label for="password">Password</label><input id="password" name="password" type="password" required><button type="submit">Sign In</button></form>
</div></body></html>
