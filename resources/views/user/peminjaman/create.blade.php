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
                        <select name="buku_id" class="form-select @error('buku_id') is-invalid @enderror">
                            <option value="">Pilih buku yang tersedia</option>
                            @foreach($buku as $b)
                                <option value="{{ $b->id }}" {{ old('buku_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->judul }} - {{ $b->pengarang }} (stok {{ $b->stok }})
                                </option>
                            @endforeach
                        </select>
                        @error('buku_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

@endsection
