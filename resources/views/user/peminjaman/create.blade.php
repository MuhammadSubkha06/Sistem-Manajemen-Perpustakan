@extends('layouts.user')
@section('title', 'Ajukan Pinjam')

@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('user.peminjaman.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Ajukan Peminjaman Buku</h4>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('user.peminjaman.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Buku</label>
                        @php
                            $selectedBook = $buku->firstWhere('id', (int) old('buku_id'));
                        @endphp

                        <input type="hidden" name="buku_id" id="buku_id" value="{{ old('buku_id') }}">

                        <div class="book-picker @error('buku_id') is-invalid @enderror">
                            <button type="button" class="book-picker-toggle" id="bookPickerToggle" aria-expanded="false">
                                <span class="book-picker-selected" id="bookPickerSelected">
                                    @if($selectedBook)
                                        <span class="book-cover-option">
                                            @if($selectedBook->cover)
                                                <img
                                                    src="{{ $selectedBook->coverUrl('S') }}"
                                                    alt="{{ $selectedBook->judul }}"
                                                    loading="lazy"
                                                    decoding="async"
                                                    referrerpolicy="no-referrer"
                                                    onerror="this.classList.add('d-none'); this.nextElementSibling?.classList.remove('d-none');"
                                                >
                                                <i class="fas fa-book-open text-primary d-none"></i>
                                            @else
                                                <i class="fas fa-book-open text-primary"></i>
                                            @endif
                                        </span>
                                        <span class="min-w-0 text-start">
                                            <span class="book-option-title">{{ $selectedBook->judul }}</span>
                                            <span class="book-option-meta">{{ $selectedBook->pengarang }} - stok {{ $selectedBook->stok }}</span>
                                        </span>
                                    @else
                                        <span class="text-muted">Pilih buku yang tersedia</span>
                                    @endif
                                </span>
                                <i class="fas fa-chevron-down text-muted"></i>
                            </button>

                            <div class="book-picker-menu" id="bookPickerMenu">
                                @forelse($buku as $b)
                                    <button
                                        type="button"
                                        class="book-picker-item {{ old('buku_id') == $b->id ? 'active' : '' }}"
                                        data-book-id="{{ $b->id }}"
                                        data-book-title="{{ $b->judul }}"
                                        data-book-author="{{ $b->pengarang }}"
                                        data-book-stock="{{ $b->stok }}"
                                    >
                                        <span class="book-cover-option">
                                            @if($b->cover)
                                                <img
                                                    src="{{ $b->coverUrl('S') }}"
                                                    alt="{{ $b->judul }}"
                                                    loading="lazy"
                                                    decoding="async"
                                                    referrerpolicy="no-referrer"
                                                    onerror="this.classList.add('d-none'); this.nextElementSibling?.classList.remove('d-none');"
                                                >
                                                <i class="fas fa-book-open text-primary d-none"></i>
                                            @else
                                                <i class="fas fa-book-open text-primary"></i>
                                            @endif
                                        </span>
                                        <span class="min-w-0 text-start">
                                            <span class="book-option-title">{{ $b->judul }}</span>
                                            <span class="book-option-meta">{{ $b->pengarang }} - stok {{ $b->stok }}</span>
                                        </span>
                                    </button>
                                @empty
                                    <div class="book-picker-empty">Belum ada buku yang tersedia.</div>
                                @endforelse
                            </div>
                        </div>
                        @error('buku_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Rencana Tanggal Kembali</label>
                        <input type="date" name="tgl_kembali_rencana" class="form-control @error('tgl_kembali_rencana') is-invalid @enderror" min="{{ today()->addDay()->toDateString() }}" value="{{ old('tgl_kembali_rencana') }}">
                        @error('tgl_kembali_rencana')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Pengajuan akan ditinjau admin terlebih dahulu sebelum buku dianggap dipinjam.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary fw-semibold px-4">
                            <i class="fas fa-paper-plane me-1"></i> Kirim Pengajuan
                        </button>
                        <a href="{{ route('user.peminjaman.index') }}" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 rounded-4 shadow-sm h-100">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h6 class="fw-bold mb-0"><i class="fas fa-circle-info me-2 text-primary"></i>Alur Pengajuan</h6>
            </div>
            <div class="card-body p-4">
                <div class="d-flex gap-3 mb-3">
                    <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fw-bold" style="width:32px;height:32px;">1</div>
                    <div>
                        <div class="fw-semibold">Ajukan buku</div>
                        <div class="text-muted small">Pilih buku dan tentukan target tanggal kembali.</div>
                    </div>
                </div>
                <div class="d-flex gap-3 mb-3">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width:32px;height:32px;">2</div>
                    <div>
                        <div class="fw-semibold">Tunggu persetujuan admin</div>
                        <div class="text-muted small">Admin akan memeriksa ketersediaan stok dan validasi pengajuan.</div>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold" style="width:32px;height:32px;">3</div>
                    <div>
                        <div class="fw-semibold">Ajukan return saat selesai</div>
                        <div class="text-muted small">Pengembalian juga perlu dikonfirmasi admin agar denda dihitung otomatis.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .book-picker {
        position: relative;
    }

    .book-picker-toggle {
        width: 100%;
        min-height: 58px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        padding: .55rem .75rem;
        border: 1px solid #dee2e6;
        border-radius: .375rem;
        background: #fff;
        color: #212529;
    }

    .book-picker.is-invalid .book-picker-toggle {
        border-color: #dc3545;
    }

    .book-picker-toggle:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 .25rem rgba(13, 110, 253, .25);
        outline: 0;
    }

    .book-picker-selected,
    .book-picker-item {
        display: flex;
        align-items: center;
        gap: .75rem;
        min-width: 0;
    }

    .min-w-0 {
        min-width: 0;
    }

    .book-picker-menu {
        position: absolute;
        z-index: 1040;
        top: calc(100% + .35rem);
        left: 0;
        right: 0;
        display: none;
        max-height: 360px;
        overflow-y: auto;
        padding: .35rem;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 12px 28px rgba(15, 23, 42, .16);
    }

    .book-picker.open .book-picker-menu {
        display: block;
    }

    .book-picker-item {
        width: 100%;
        padding: .5rem;
        border: 0;
        border-radius: 8px;
        background: transparent;
        text-align: left;
    }

    .book-picker-item:hover,
    .book-picker-item.active {
        background: #eff6ff;
    }

    .book-cover-option {
        width: 42px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
        border-radius: 6px;
        background: #eef2f7;
    }

    .book-cover-option img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .book-option-title,
    .book-option-meta {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .book-option-title {
        font-weight: 600;
        font-size: .92rem;
    }

    .book-option-meta {
        color: #6b7280;
        font-size: .8rem;
        margin-top: .1rem;
    }

    .book-picker-empty {
        padding: .75rem;
        color: #6b7280;
        font-size: .88rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const picker = document.querySelector('.book-picker');
        const toggle = document.getElementById('bookPickerToggle');
        const selected = document.getElementById('bookPickerSelected');
        const input = document.getElementById('buku_id');

        if (!picker || !toggle || !selected || !input) {
            return;
        }

        toggle.addEventListener('click', function () {
            const isOpen = picker.classList.toggle('open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.querySelectorAll('.book-picker-item').forEach(function (item) {
            item.addEventListener('click', function () {
                input.value = item.dataset.bookId;
                selected.innerHTML = item.innerHTML;

                document.querySelectorAll('.book-picker-item').forEach(function (option) {
                    option.classList.remove('active');
                });
                item.classList.add('active');

                picker.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            });
        });

        document.addEventListener('click', function (event) {
            if (!picker.contains(event.target)) {
                picker.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    });
</script>
@endpush

@endsection
