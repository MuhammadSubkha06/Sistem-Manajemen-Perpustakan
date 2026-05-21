<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - Perpustakaan 40</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo40.png') }}">
    <link rel="preconnect" href="https://covers.openlibrary.org" crossorigin>
    <link rel="dns-prefetch" href="//covers.openlibrary.org">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --sidebar-width: 230px;
            --accent: #FFC107;
            --accent-hover: #e5ac00;
            --sidebar-bg: #1a1d23;
            --sidebar-hover: rgba(255, 255, 255, .07);
        }

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: #f4f6f9;
            overflow-x: hidden;
        }

        #mainSidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            z-index: 1050;
            transition: transform .28s cubic-bezier(.4, 0, .2, 1);
        }

        .sidebar-brand {
            padding: 1.1rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        .sidebar-brand .logo-wrap {
            width: 34px;
            height: 34px;
            background: var(--accent);
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .sidebar-brand .logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .brand-name {
            font-size: .9rem;
            font-weight: 700;
            color: #fff;
            line-height: 1;
        }

        .brand-sub {
            font-size: .6rem;
            color: #8b8fa8;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-top: 2px;
        }

        .nav-section-label {
            font-size: .6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #6b7280;
            padding: .5rem .75rem .25rem;
            margin-top: .5rem;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .5rem .75rem;
            border-radius: 8px;
            margin-bottom: 2px;
            color: #9ca3af;
            text-decoration: none;
            font-size: .85rem;
            font-weight: 500;
            transition: background .15s, color .15s;
        }

        .sidebar-nav a:hover {
            background: var(--sidebar-hover);
            color: #e5e7eb;
        }

        .sidebar-nav a.active {
            background: var(--accent);
            color: #1a1d23 !important;
            font-weight: 700;
        }

        .sidebar-nav a .fa-fw {
            width: 16px;
            text-align: center;
        }

        .sidebar-nav a.sub-link {
            padding-left: 2.1rem;
        }

        .sidebar-scroll {
            flex: 1;
            overflow-y: auto;
            padding: .5rem .75rem;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .15);
            border-radius: 2px;
        }

        .sidebar-footer {
            padding: .75rem;
            border-top: 1px solid rgba(255, 255, 255, .08);
        }

        .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .5rem .75rem;
            border-radius: 8px;
            color: #9ca3af;
            text-decoration: none;
            font-size: .85rem;
            transition: background .15s, color .15s;
        }

        .sidebar-footer a:hover {
            background: rgba(239, 68, 68, .15);
            color: #f87171;
        }

        #mobileTopbar {
            display: none;
            position: sticky;
            top: 0;
            z-index: 1030;
            height: 56px;
            background: var(--sidebar-bg);
            padding: 0 1rem;
        }

        #sidebarOverlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .55);
            z-index: 1040;
        }

        .page-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            min-width: 0;
            padding: 1.5rem;
        }

        .stat-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .07);
        }

        .stat-card .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
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

        .page-loading-screen {
            position: fixed;
            inset: 0;
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(244, 246, 249, .94);
            opacity: 0;
            pointer-events: none;
            transition: opacity .2s ease;
        }

        .page-loading-screen.show {
            opacity: 1;
            pointer-events: auto;
        }

        .loading-panel {
            width: min(320px, calc(100vw - 2rem));
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 18px 40px rgba(15, 23, 42, .14);
            text-align: center;
        }

        .loading-logo {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 10px;
        }

        @media (max-width: 991.98px) {
            #mobileTopbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            #mainSidebar {
                position: fixed !important;
                top: 0;
                left: 0;
                transform: translateX(-100%);
                height: 100vh !important;
            }

            #mainSidebar.sidebar-open {
                transform: translateX(0);
            }

            #sidebarOverlay.open {
                display: block;
            }

            .page-wrapper {
                flex-direction: column;
            }

            .main-content {
                padding: 1rem;
            }
        }

        @media (min-width: 992px) {
            #mobileTopbar {
                display: none !important;
            }
        }
    </style>
    <div id="toastContainer" class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
    </div>
    @stack('styles')
</head>

<body>

    <div id="pageLoadingScreen" class="page-loading-screen" aria-hidden="true">
        <div class="loading-panel">
            <img src="{{ asset('images/logo40.png') }}" alt="Logo Perpustakaan 40" class="loading-logo mb-3">
            <div class="fw-bold mb-1">Memuat halaman</div>
            <div class="text-muted small mb-3">Menyiapkan data dan cover buku...</div>
            <div class="progress" role="progressbar" aria-label="Loading" style="height: 6px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" style="width: 100%">
                </div>
            </div>
        </div>
    </div>

    <div id="mobileTopbar">
        <div class="d-flex align-items-center gap-2">
            <div class="logo-wrap d-flex align-items-center justify-content-center rounded-2 overflow-hidden flex-shrink-0"
                style="width:30px;height:30px;background:var(--accent);">
                <img src="{{ asset('images/logo40.png') }}" alt="Logo Perpustakaan 40"
                    style="width:100%;height:100%;object-fit:cover;">
            </div>
            <span class="fw-bold text-white" style="font-size:.9rem;">Perpustakaan 40</span>
        </div>
        <button class="btn btn-link text-white p-1" id="sidebarToggle">
            <i class="fas fa-bars fa-lg"></i>
        </button>
    </div>

    <div id="sidebarOverlay" onclick="closeSidebar()"></div>

    <div class="page-wrapper">
        <aside id="mainSidebar">
            <div class="sidebar-brand d-none d-lg-flex align-items-center gap-2">
                <div class="logo-wrap d-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/logo40.png') }}" alt="Logo Perpustakaan 40">
                </div>
                <div>
                    <div class="brand-name">Perpustakaan 40</div>
                    <div class="brand-sub">Admin Panel</div>
                </div>
            </div>

            <div class="d-flex d-lg-none align-items-center justify-content-between px-3 py-3 border-bottom"
                style="border-color:rgba(255,255,255,.08)!important;">
                <span class="fw-bold text-white" style="font-size:.85rem;">Menu</span>
                <button class="btn btn-link text-secondary p-0" onclick="closeSidebar()">
                    <i class="fas fa-xmark fa-lg"></i>
                </button>
            </div>

            <div class="sidebar-scroll">
                <nav class="sidebar-nav">
                    <div class="nav-section-label">Menu Utama</div>

                    <a href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-gauge fa-fw"></i> Dashboard
                    </a>

                    <a href="{{ route('admin.books.index') }}"
                        class="{{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
                        <i class="fas fa-book fa-fw"></i> Buku
                    </a>

                    <a href="{{ route('admin.categories.index') }}"
                        class="sub-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="fas fa-tags fa-fw"></i> Kategori
                    </a>

                    <a href="{{ route('admin.members.index') }}"
                        class="{{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                        <i class="fas fa-users fa-fw"></i> Anggota
                    </a>

                    <div class="nav-section-label">Transaksi</div>

                    <a href="{{ route('admin.peminjaman.index') }}"
                        class="{{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
                        <i class="fas fa-arrow-right-arrow-left fa-fw"></i> Peminjaman
                    </a>

                    <a href="{{ route('admin.pengembalian.index') }}"
                        class="{{ request()->routeIs('admin.pengembalian.*') ? 'active' : '' }}">
                        <i class="fas fa-rotate-left fa-fw"></i> Pengembalian
                    </a>

                    <a href="{{ route('admin.denda.index') }}"
                        class="{{ request()->routeIs('admin.denda.*') ? 'active' : '' }}">
                        <i class="fas fa-triangle-exclamation fa-fw"></i> Denda
                    </a>

                    <a href="{{ route('admin.struk.index') }}"
                        class="{{ request()->routeIs('admin.struk.*') ? 'active' : '' }}">
                        <i class="fas fa-receipt fa-fw"></i> Struk
                    </a>

                    <div class="nav-section-label">Laporan & Monitoring</div>

                    <a href="{{ route('admin.report.index') }}"
                        class="{{ request()->routeIs('admin.report.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-column fa-fw"></i> Laporan Bulanan
                    </a>

                    <a href="{{ route('admin.violations.index') }}"
                        class="{{ request()->routeIs('admin.violations.*') ? 'active' : '' }}">
                        <i class="fas fa-warning fa-fw"></i> Pelanggaran
                    </a>
                </nav>
            </div>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn p-0 w-100 text-start" style="background:none;border:none;">
                        <div class="d-flex align-items-center gap-2 px-1 py-2 rounded-2 text-secondary"
                            style="font-size:.85rem;transition:all .15s;"
                            onmouseover="this.style.color='#f87171';this.style.background='rgba(239,68,68,.1)'"
                            onmouseout="this.style.color='';this.style.background=''">
                            <i class="fas fa-arrow-right-from-bracket fa-fw"></i> Keluar
                        </div>
                    </button>
                </form>
            </div>
        </aside>

        <main class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3" role="alert">
                    <i class="fas fa-circle-check me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-3" role="alert">
                    <i class="fas fa-circle-xmark me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            const loader = document.getElementById('pageLoadingScreen');
            if (!loader) {
                return;
            }

            let showTimer = window.setTimeout(() => loader.classList.add('show'), 650);

            function hideLoader() {
                window.clearTimeout(showTimer);
                loader.classList.remove('show');
            }

            function showLoader() {
                window.clearTimeout(showTimer);
                loader.classList.add('show');
            }

            window.addEventListener('load', hideLoader);
            window.addEventListener('pageshow', hideLoader);

            document.addEventListener('submit', function () {
                showLoader();
            });

            document.addEventListener('click', function (event) {
                const link = event.target.closest('a[href]');
                if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                    return;
                }

                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || link.target === '_blank' || link.hasAttribute('download')) {
                    return;
                }

                const url = new URL(link.href, window.location.href);
                if (url.origin === window.location.origin && url.href !== window.location.href) {
                    showLoader();
                }
            });
        })();

        function openSidebar() {
            document.getElementById('mainSidebar').classList.add('sidebar-open');
            document.getElementById('sidebarOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            document.getElementById('mainSidebar').classList.remove('sidebar-open');
            document.getElementById('sidebarOverlay').classList.remove('open');
            document.body.style.overflow = '';
        }
        document.getElementById('sidebarToggle')?.addEventListener('click', openSidebar);
    </script>
    @stack('scripts')
</body>

</html>