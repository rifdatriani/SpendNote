<!DOCTYPE html>
<html lang="id">

<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/alokasi.css') }}">

<head>
    <meta charset="UTF-8">
    <title>SpendNote</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        .navbar-custom {
            background: #ffffff;
            padding: 15px 25px;
            border-bottom: 1px solid #eee;
        }
        .navbar-custom img.logo {
            height: 35px;
        }
        .profile-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 20px;
        }
        .notif-icon {
            font-size: 22px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    {{-- TOP NAVBAR --}}
    <nav class="navbar-custom d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center gap-2">
            <img class="logo" src="{{ asset('assets/logo.png') }}" alt="SpendNote">
        </div>


        <div class="d-flex align-items-center gap-4">
            <span class="notif-icon">🔔</span>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('akun.index') }}" class="d-flex align-items-center gap-2 text-decoration-none text-dark">
                    <div class="profile-icon">👤</div>
                    <span>Halo, {{ Auth::user()->name }}</span>
                </a>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="mt-4">
        @yield('content')
    </main>

    {{-- PENTING: agar script dinamis bekerja --}}
    @yield('scripts')

</body>
</html>
