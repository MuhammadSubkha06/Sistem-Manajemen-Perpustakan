<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal') - Perpustakaan 40</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo40.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: #f6f7fb;
            color: #1f2937;
        }

        .navbar {
            border-bottom: 1px solid #e5e7eb;
        }

        .navbar-brand img {
            width: 32px;
            height: 32px;
            object-fit: cover;
            border-radius: 8px;
        }

        .nav-link {
            font-size: .88rem;
            font-weight: 600;
            color: #6b7280;
            border-radius: 8px;
            padding: .45rem .75rem !important;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #0d6efd;
            background: #eff6ff;
        }

        .main-content {
            max-width: 1180px;
            margin: 0 auto;
            padding: 1.25rem;
        }

        .simple-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: none;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
        }

        .page-subtitle {
            color: #6b7280;
            font-size: .88rem;
            margin: .25rem 0 0;
        }

        .book-cover-sm {
            width: 64px;
            height: 86px;
            border-radius: 8px;
            background: #eff6ff;
            overflow: hidden;
            flex-shrink: 0;
        }

        .book-cover-sm img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .badge-status-dipinjam {
            background: #fff3cd;
            color: #856404;
        }

        .badge-status-dikembalikan {
            background: #d1e7dd;
            color: #0a3622;
        }

        .badge-status-terlambat {
            background: #f8d7da;
            color: #58151c;
        }
    </style>
    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-white sticky-top">
        <div class="container-fluid px-3 px-lg-4">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('user.dashboard') }}">
                <img src="{{ asset('images/logo40.png') }}" alt="Logo Perpustakaan 40">
                <span>Perpustakaan 40</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#userNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="userNavbar">
                <div class="navbar-nav ms-lg-4 gap-lg-1">
                    <a href="{{ route('user.dashboard') }}"
                        class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-house me-1"></i> Beranda
                    </a>
                    <a href="{{ route('user.books.index') }}"
                        class="nav-link {{ request()->routeIs('user.books.*') ? 'active' : '' }}">
                        <i class="fas fa-book-open me-1"></i> Katalog
                    </a>
                    <a href="{{ route('user.peminjaman.index') }}"
                        class="nav-link {{ request()->routeIs('user.peminjaman.*') ? 'active' : '' }}">
                        <i class="fas fa-clock-rotate-left me-1"></i> Peminjaman
                    </a>
                    <a href="{{ route('user.struk.index') }}"
                        class="nav-link {{ request()->routeIs('user.struk.*') ? 'active' : '' }}">
                        <i class="fas fa-receipt me-1"></i> Struk
                    </a>
                    <a href="{{ route('user.profile') }}"
                        class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                        <i class="fas fa-user me-1"></i> Profile
                    </a>
                </div>

                <div class="ms-lg-auto mt-3 mt-lg-0 d-flex align-items-lg-center gap-2">
                    <span class="small text-muted d-none d-lg-inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-right-from-bracket me-1"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-3">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-3">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>