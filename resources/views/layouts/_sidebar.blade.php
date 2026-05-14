
<div class="d-flex d-lg-none align-items-center justify-content-between px-3 py-2 bg-dark text-white"
    style="position:sticky;top:0;z-index:1030;height:56px;">
    <div class="d-flex align-items-center gap-2">
        <div class="d-flex align-items-center justify-content-center bg-warning rounded-2 overflow-hidden flex-shrink-0"
            style="width:30px;height:30px;">
            <img src="{{ asset('images/logo40.png') }}" alt="Logo" style="width:100%;height:100%;object-fit:cover;"
                 onerror="this.style.display='none'">
        </div>
        <div class="fw-bold text-white" style="font-size:.9rem;">Perpustakaan 40</div>
    </div>
    <button class="btn btn-link text-white p-1" id="sidebarToggle" aria-label="Toggle menu">
        <i class="fas fa-bars fa-lg"></i>
    </button>
</div>

<div id="sidebarOverlay" class="d-none"
    style="position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1040;"
    onclick="closeSidebar()">
</div>

<div id="mainSidebar"
    class="d-flex flex-column bg-dark text-white flex-shrink-0"
    style="width:230px;height:100vh;position:sticky;top:0;z-index:1050;">

    {{-- Logo (desktop) --}}
    <div class="d-none d-lg-flex align-items-center gap-2 px-3 py-4 border-bottom border-secondary">
        <div class="d-flex align-items-center justify-content-center bg-warning rounded-2 flex-shrink-0 overflow-hidden"
            style="width:34px;height:34px;">
            <img src="{{ asset('images/logo40.png') }}" alt="Logo SMKN 40"
                 style="width:100%;height:100%;object-fit:cover;"
                 onerror="this.style.display='none'">
        </div>
        <div>
            <div class="fw-bold lh-1 text-white" style="font-size:.9rem;">Perpustakaan 40</div>
            <div class="text-secondary lh-1 mt-1"
                style="font-size:.65rem;letter-spacing:.06em;text-transform:uppercase;">Admin Panel</div>
        </div>
    </div>

    <div class="d-flex d-lg-none align-items-center justify-content-between px-3 py-3 border-bottom border-secondary">
        <div class="fw-bold text-white" style="font-size:.85rem;">Menu</div>
        <button class="btn btn-link text-secondary p-0" onclick="closeSidebar()">
            <i class="fas fa-xmark fa-lg"></i>
        </button>
    </div>

    <nav class="flex-grow-1 px-2 pt-3 overflow-y-auto">

        <div class="text-secondary text-uppercase fw-bold px-2 mb-2"
             style="font-size:.6rem;letter-spacing:.12em;">Menu Utama</div>

        @if(auth()->user()->isAdmin())
            <x-nav-link href="{{ route('dashboard') }}" icon="gauge" label="Beranda" />
        @endif

        <x-nav-link href="{{ route('buku.index') }}" icon="book" label="Buku" />

        @if(auth()->user()->isAdmin())
            @php
                $katActive = request()->routeIs('kategori.*');
                $katCls = $katActive
                    ? 'nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-2 mb-1 fw-semibold text-white bg-warning'
                    : 'nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-2 mb-1 text-secondary';
            @endphp
            <a href="{{ route('kategori.index') }}" class="{{ $katCls }}"
               style="padding-left:2.2rem !important;">
                <i class="fas fa-tags fa-fw"></i> Kategori
            </a>
        @endif

        <x-nav-link href="{{ route('anggota.index') }}" icon="users" label="Anggota" />

        <div class="text-secondary text-uppercase fw-bold px-2 mt-3 mb-2"
             style="font-size:.6rem;letter-spacing:.12em;">Transaksi</div>

        <x-nav-link href="{{ route('peminjaman.index') }}" icon="arrow-right-arrow-left" label="Peminjaman" />
        <x-nav-link href="{{ route('peminjaman.index') }}?status=dikembalikan" icon="rotate-left" label="Pengembalian" />

        @if(auth()->user()->isAdmin())
            <x-nav-link href="{{ route('denda.index') }}" icon="triangle-exclamation" label="Denda" />
        @endif

    </nav>

    <div class="px-2 pb-3 border-top border-secondary pt-3 mt-auto">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-2 text-secondary w-100 border-0 bg-transparent hover-logout">
                <i class="fas fa-arrow-right-from-bracket fa-fw"></i> Keluar
            </button>
        </form>
    </div>
</div>

<style>
    .hover-logout:hover {
        color: white !important;
        background-color: rgba(255, 255, 255, 0.1) !important;
    }

    @media (min-width: 992px) {
        #mainSidebar {
            position: sticky !important;
            top: 0;
            transform: none !important;
            display: flex !important;
        }
        #sidebarOverlay { display: none !important; }
    }

    @media (max-width: 991.98px) {
        #mainSidebar {
            position: fixed !important;
            top: 0;
            left: 0;
            height: 100vh !important;
            transform: translateX(-100%);
            transition: transform .28s cubic-bezier(.4, 0, .2, 1);
            z-index: 1050;
        }
        #mainSidebar.sidebar-open { transform: translateX(0); }
        .page-wrapper { flex-direction: column !important; }
    }
</style>

<script>
    function openSidebar() {
        document.getElementById('mainSidebar').classList.add('sidebar-open');
        document.getElementById('sidebarOverlay').classList.remove('d-none');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        document.getElementById('mainSidebar').classList.remove('sidebar-open');
        document.getElementById('sidebarOverlay').classList.add('d-none');
        document.body.style.overflow = '';
    }

    document.getElementById('sidebarToggle')?.addEventListener('click', openSidebar);
</script>