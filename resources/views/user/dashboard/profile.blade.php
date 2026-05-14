@extends('layouts.user')
@section('title', 'Profil Saya')

@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('user.dashboard') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Profil Saya</h4>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 rounded-4 shadow-sm h-100">
            <div class="card-body p-4 text-center">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center fw-bold mb-3" style="width: 78px; height: 78px; font-size: 1.8rem;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <h5 class="fw-bold mb-1">{{ auth()->user()->name }}</h5>
                <div class="text-muted">{{ auth()->user()->email }}</div>
                <hr>
                <div class="text-start small">
                    <div class="mb-2"><span class="text-muted">NIS:</span> <span class="fw-semibold">{{ $anggota->nis ?? '-' }}</span></div>
                    <div class="mb-2"><span class="text-muted">Kelas:</span> <span class="fw-semibold">{{ $anggota->kelas ?? '-' }}</span></div>
                    <div><span class="text-muted">Role:</span> <span class="fw-semibold">Anggota</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('user.profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nomor HP</label>
                        <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $anggota->no_hp ?? '') }}">
                        @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="alamat" rows="4" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $anggota->alamat ?? '') }}</textarea>
                        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button class="btn btn-primary fw-semibold px-4">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
