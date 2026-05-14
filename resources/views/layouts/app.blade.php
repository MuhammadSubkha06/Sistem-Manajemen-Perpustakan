<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/logo40.png') }}">
    <title>Perpustakaan 40</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .wrapper { display: flex; min-height: 100vh; }
        .main-content { flex-grow: 1; background: #f8fafc; }
        /* CSS Tambahan agar Sidebar tetap konsisten */
        @media (min-width: 992px) {
            #mainSidebar { min-width: 250px; max-width: 250px; position: sticky; top: 0; height: 100vh; }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="wrapper">

        <div class="main-content">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm px-3">
                <div class="container-fluid">
                    <span class="navbar-text text-white d-none d-lg-block">
                        Selamat Datang, <strong>{{ Auth::user()->name }}</strong>
                    </span>

                    <div class="ms-auto d-flex align-items-center gap-3">
                        <div class="text-white d-lg-none">{{ Auth::user()->name }}</div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-sign-out-alt me-1"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <div class="container-fluid p-4">
                {{-- Bagian untuk menampilkan pesan sukses/error --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
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
