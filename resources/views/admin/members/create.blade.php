@extends('layouts.admin')
@section('title', 'Tambah User Anggota')

@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('admin.members.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Tambah User Anggota</h4>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-xl-8">
        <div class="card border-0 rounded-3 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.members.store') }}">
                    @csrf

                    @include('admin.members._form')

                    <div class="d-flex gap-2 mt-4">
                        <button class="btn btn-warning fw-semibold px-4">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                        <a href="{{ route('admin.members.index') }}" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
