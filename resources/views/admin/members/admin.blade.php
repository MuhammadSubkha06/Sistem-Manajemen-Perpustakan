<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan 40') — Perpustakaan 40</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo40.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f1f5f9; }
        /* Sidebar */
        #sidebar { width: 240px; min-width: 240px; background: #1e293b; transition: all .25s; }
        #sidebar .nav-link { color: #94a3b8; border-radius: 8px; padding: .55rem 1rem; font-size: .88rem; }
        #sidebar .nav-link:hover, #sidebar .nav-link.active { background: rgba(255,255,255,.08); color: #fff; }
        #sidebar .nav-link .fa-fw { width: 18px; }
        #sidebar .sidebar-brand { color: #f8fafc; font-weight: 700; font-size: 1.05rem; }
        @media (max-width: 991.98px) { #sidebar { display: none !important; } }
    </style>
    @stack('styles')
</head>
<body>

<div class="d-flex" style="min-height:100vh;">

    {{-- ── Sidebar ── --}}
    <div id="sidebar" class="d-none d-lg-flex flex-column p-3 gap-1">
        <div class="sidebar-brand d-flex align-items-center gap-2 px-2 py-3 mb-2">
            <i class="fas fa-book-open text-warning"></i>
            <span>Perpustakaan 40</span>
        </div>

        <small class="text-secondary px-2 mb-1 text-uppercase fw-semibold" style="font-size:.65rem;letter-spacing:.08em;">Menu</small>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-house-chimney fa-fw"></i> Dashboard
        </a>
        <a href="{{ route('admin.books.index') }}"
           class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
            <i class="fas fa-book fa-fw"></i> Manajemen Buku
        </a>
        <a href="{{ route('admin.categories.index') }}"
           class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="fas fa-tags fa-fw"></i> Kategori
        </a>
        <a href="{{ route('admin.members.index') }}"
           class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
            <i class="fas fa-users fa-fw"></i> Manajemen Anggota
        </a>
        <a href="{{ route('admin.peminjaman.index') }}"
           class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
            <i class="fas fa-arrow-right-arrow-left fa-fw"></i> Peminjaman
        </a>
        <a href="{{ route('admin.pengembalian.index') }}"
           class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.pengembalian.*') ? 'active' : '' }}">
            <i class="fas fa-rotate-left fa-fw"></i> Pengembalian
        </a>
        <a href="{{ route('admin.denda.index') }}"
           class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.denda.*') ? 'active' : '' }}">
            <i class="fas fa-triangle-exclamation fa-fw"></i> Rekap Denda
        </a>

        <div class="mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link d-flex align-items-center gap-2 w-100 border-0 bg-transparent text-start">
                    <i class="fas fa-right-from-bracket fa-fw"></i> Logout
                </button>
            </form>
        </div>
    </div>

    {{-- ── Main Content ── --}}
    <div class="flex-grow-1 overflow-auto" style="min-width:0;">
        <div class="p-3 p-lg-4">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 rounded-3 small py-2 mb-3 border-0 shadow-sm">
                    <i class="fas fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible d-flex align-items-center gap-2 rounded-3 small py-2 mb-3 border-0 shadow-sm">
                    <i class="fas fa-circle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible rounded-3 small py-2 mb-3 border-0 shadow-sm">
                    <i class="fas fa-circle-exclamation me-1"></i>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
