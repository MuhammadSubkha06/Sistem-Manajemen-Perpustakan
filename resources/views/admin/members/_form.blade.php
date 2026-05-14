<div class="row g-3">
    <div class="col-12">
        <div class="alert alert-info border-0 rounded-3 mb-0" style="font-size:.85rem;">
            <div class="fw-semibold mb-1">
                <i class="fas fa-id-card me-1"></i> Akun Login Anggota
            </div>
            NIS digunakan sebagai username login user. Password disimpan aman di tabel users.
        </div>
    </div>

    <div class="col-md-6">
        <label for="nis" class="form-label small fw-semibold">NIS</label>
        <input
            type="text"
            id="nis"
            name="nis"
            class="form-control @error('nis') is-invalid @enderror"
            value="{{ old('nis', $member->nis ?? '') }}"
            placeholder="Masukkan NIS anggota"
            required
        >
        @error('nis')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="nama" class="form-label small fw-semibold">Nama Lengkap</label>
        <input
            type="text"
            id="nama"
            name="nama"
            class="form-control @error('nama') is-invalid @enderror"
            value="{{ old('nama', $member->nama ?? '') }}"
            placeholder="Masukkan nama lengkap"
            required
        >
        @error('nama')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="kelas" class="form-label small fw-semibold">Kelas</label>
        <input
            type="text"
            id="kelas"
            name="kelas"
            class="form-control @error('kelas') is-invalid @enderror"
            value="{{ old('kelas', $member->kelas ?? '') }}"
            placeholder="Contoh: XII RPL 1"
            required
        >
        @error('kelas')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="no_hp" class="form-label small fw-semibold">Nomor HP</label>
        <input
            type="text"
            id="no_hp"
            name="no_hp"
            class="form-control @error('no_hp') is-invalid @enderror"
            value="{{ old('no_hp', $member->no_hp ?? '') }}"
            placeholder="08xxxxxxxxxx"
        >
        @error('no_hp')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="password" class="form-label small fw-semibold">
            Password Login
            @empty($member)
                <span class="text-danger">*</span>
            @endempty
        </label>
        <input
            type="password"
            id="password"
            name="password"
            class="form-control @error('password') is-invalid @enderror"
            placeholder="{{ isset($member) ? 'Kosongkan jika tidak diubah' : 'Minimal 6 karakter' }}"
            {{ isset($member) ? '' : 'required' }}
        >
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="password_confirmation" class="form-label small fw-semibold">
            Konfirmasi Password
            @empty($member)
                <span class="text-danger">*</span>
            @endempty
        </label>
        <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            class="form-control @error('password_confirmation') is-invalid @enderror"
            placeholder="Ulangi password"
            {{ isset($member) ? '' : 'required' }}
        >
        @error('password_confirmation')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="alamat" class="form-label small fw-semibold">Alamat</label>
        <textarea
            id="alamat"
            name="alamat"
            rows="4"
            class="form-control @error('alamat') is-invalid @enderror"
            placeholder="Masukkan alamat anggota"
        >{{ old('alamat', $member->alamat ?? '') }}</textarea>
        @error('alamat')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
