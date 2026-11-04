<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Doctor Management') - Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f3f7f9;
            color: #19364a;
            font-family: 'Hind Siliguri', sans-serif;
        }

        .admin-top {
            background: #10334b;
            color: #fff;
            padding: 15px 4%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .admin-top a {
            color: #fff;
            text-decoration: none;
        }

        .admin-wrap {
            max-width: 1200px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .admin-card {
            background: #fff;
            border: 1px solid #e1e9ee;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 8px 25px #12334d08;
        }

        .admin-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn {
            border: 0;
            border-radius: 8px;
            padding: 10px 16px;
            background: #078d9b;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
            font: inherit;
        }

        .btn-muted {
            background: #eaf1f4;
            color: #23465a;
        }

        .btn-danger {
            background: #b83b48;
        }

        .field {
            margin-bottom: 16px;
        }

        .field label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .field input,
        .field textarea,
        .field select {
            width: 100%;
            padding: 11px;
            border: 1px solid #d5e0e6;
            border-radius: 8px;
            font: inherit;
        }

        .field textarea {
            min-height: 100px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 12px;
            border-bottom: 1px solid #e8eef1;
            text-align: left;
            font-size: 14px;
        }

        .table th {
            background: #f7fafb;
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            background: #e5f6ee;
            color: #17643d;
            margin-bottom: 15px;
        }

        .errors {
            background: #fff0f0;
            color: #9b2525;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .muted {
            color: #70818b;
            font-size: 13px;
        }

        .check-row {
            display: flex;
            gap: 9px;
            align-items: center;
            margin: 7px 0;
        }

        .check-row input {
            width: auto;
        }

        .schedule-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .schedule-grid th,
        .schedule-grid td {
            border: 1px solid #e1e9ee;
            padding: 8px;
            text-align: left;
            font-size: 13px;
        }

        .schedule-grid input[type="time"] {
            width: 120px;
            padding: 7px;
            border: 1px solid #d5e0e6;
            border-radius: 6px;
        }

        @media (max-width: 700px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .table-wrap {
                overflow-x: auto;
            }

            .admin-card {
                padding: 15px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <header class="admin-top">

        <strong>
            <a href="{{ route('admin.dashboard') }}">
                CarePlus Admin
            </a>
            / Doctors
        </strong>

        <nav class="admin-actions">

            <a href="{{ route('doctors.index') }}" target="_blank">
                View Website
            </a>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf

                <button type="submit" class="btn btn-muted">
                    Logout
                </button>
            </form>

        </nav>

    </header>

    <main class="admin-wrap">

        {{-- Success Message --}}
        @if (session('status'))
            <div class="alert">
                {{ session('status') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="errors">

                <strong>Please correct the following:</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif

        {{-- Page Content --}}
        @yield('content')

    </main>

    @stack('scripts')

</body>

</html>
