@extends('layouts.admin')
@section('title', 'Detail Pelanggaran: ' . $anggota->nama)

@section('content')

    {{-- ── Back + Member Card ─────────────────────────────────────── --}}
    <div class="mb-4">
        <a href="{{ route('admin.violations.index') }}" class="btn btn-outline-secondary rounded-2 mb-3">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>

        <div class="card border-0 rounded-3 shadow-sm">
            <div class="card-body p-4">

                {{-- Member info + status ─────────────────────────── --}}
                <div class="row mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <h5 class="fw-bold mb-1">{{ $anggota->nama }}</h5>
                        <p class="text-muted mb-1"><i class="fas fa-id-card me-2"></i>{{ $anggota->nis }}</p>
                        <p class="text-muted mb-1"><i class="fas fa-book me-2"></i>{{ $anggota->kelas }}</p>
                        <p class="text-muted mb-0"><i class="fas fa-phone me-2"></i>{{ $anggota->no_hp ?? '-' }}</p>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            @if($anggota->isSuspended())
                                <span class="badge bg-danger fs-6 px-3 py-2">
                                    <i class="fas fa-ban me-1"></i>Suspended
                                </span>
                                <button class="btn btn-sm btn-success rounded-2" onclick="unsuspendMember()">
                                    <i class="fas fa-unlock me-1"></i>Unsuspend
                                </button>
                            @else
                                <span class="badge bg-success fs-6 px-3 py-2">
                                    <i class="fas fa-check me-1"></i>Aktif
                                </span>
                                <button class="btn btn-sm btn-danger rounded-2" onclick="showSuspendModal()">
                                    <i class="fas fa-ban me-1"></i>Suspend
                                </button>
                            @endif
                        </div>

                        @if($anggota->suspended_at)
                            <p class="text-danger small mb-1">
                                <i class="fas fa-calendar-times me-1"></i>
                                <strong>Suspend sejak:</strong>
                                {{ $anggota->suspended_at->translatedFormat('d M Y H:i') }}
                            </p>
                            <p class="text-muted small mb-0">
                                <i class="fas fa-comment-alt me-1"></i>
                                <strong>Alasan:</strong> {{ $anggota->suspension_reason ?? '-' }}
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Statistik ──────────────────────────────────────── --}}
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="rounded-3 bg-danger bg-opacity-10 p-3 text-center h-100">
                            <div class="fs-4 fw-bold text-danger">{{ $anggota->getDendaCount() }}</div>
                            <div class="small text-muted mt-1">Pelanggaran Denda</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="rounded-3 bg-warning bg-opacity-10 p-3 text-center h-100">
                            <div class="fs-4 fw-bold text-warning">{{ $anggota->getLateReturnCount() }}</div>
                            <div class="small text-muted mt-1">Pengembalian Terlambat</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ── Violation History ──────────────────────────────────────── --}}
    <div class="card border-0 rounded-3 shadow-sm">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Riwayat Pelanggaran</h6>
            <button class="btn btn-sm btn-primary rounded-2" onclick="showAddViolationModal()">
                <i class="fas fa-plus me-1"></i>Tambah Pelanggaran
            </button>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Jenis</th>
                            <th class="px-4 py-3 text-center">Jumlah</th>
                            <th class="px-4 py-3">Nominal</th>
                            <th class="px-4 py-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($violations as $violation)
                            <tr>
                                <td class="px-4 py-3 text-muted small">
                                    {{ $violation->created_at->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="badge {{ $violation->getBadgeClass() }}">
                                        {{ $violation->getTypeLabel() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center fw-semibold">
                                    {{ $violation->count }}×
                                </td>
                                <td class="px-4 py-3">{{ $violation->getFormattedAmount() }}</td>
                                <td class="px-4 py-3 text-muted small">{{ $violation->description ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-5 text-center text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                    Belum ada riwayat pelanggaran
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-top">
                {{ $violations->links() }}
            </div>
        </div>
    </div>

    {{-- ── Modal Suspend ─────────────────────────────────────────── --}}
    <div class="modal fade" id="suspendModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-3">
                <div class="modal-header border-bottom py-3 px-4">
                    <h6 class="modal-title fw-bold">Suspend Anggota</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <p class="mb-3">
                        Suspend anggota <strong>{{ $anggota->nama }}</strong>?
                        Anggota tidak dapat login selama status ini aktif.
                    </p>
                    <div>
                        <label class="form-label fw-semibold">
                            Alasan Suspend <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control rounded-2" id="suspendReason" rows="3"
                            placeholder="Masukkan alasan suspend..."></textarea>
                        <div class="invalid-feedback" id="suspendReasonError">
                            Alasan suspend harus diisi.
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3 px-4">
                    <button type="button" class="btn btn-secondary rounded-2" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger rounded-2" id="confirmSuspendBtn"
                        onclick="confirmSuspend()">
                        <i class="fas fa-ban me-1"></i>Suspend
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Modal Add Violation ───────────────────────────────────── --}}
    <div class="modal fade" id="addViolationModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-3">
                <div class="modal-header border-bottom py-3 px-4">
                    <h6 class="modal-title fw-bold">Tambah Pelanggaran</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Jenis Pelanggaran <span class="text-danger">*</span>
                        </label>
                        <select class="form-select rounded-2" id="violationType">
                            <option value="">Pilih jenis pelanggaran</option>
                            <option value="denda">Denda</option>
                            <option value="late_return">Pengembalian Terlambat</option>
                            <option value="damage">Kerusakan Buku</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Jumlah <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control rounded-2" id="violationCount" min="1" placeholder="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nominal (opsional)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control rounded-end-2" id="violationAmount" min="0"
                                placeholder="0">
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea class="form-control rounded-2" id="violationDescription" rows="3"
                            placeholder="Masukkan keterangan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top py-3 px-4">
                    <button type="button" class="btn btn-secondary rounded-2" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary rounded-2" id="confirmViolationBtn"
                        onclick="confirmAddViolation()">
                        <i class="fas fa-plus me-1"></i>Tambah
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // ── Suspend ────────────────────────────────────────────────────
        function showSuspendModal() {
            document.getElementById('suspendReason').value = '';
            document.getElementById('suspendReason').classList.remove('is-invalid');
            new bootstrap.Modal(document.getElementById('suspendModal')).show();
        }

        async function confirmSuspend() {
            const reason = document.getElementById('suspendReason').value.trim();
            const reasonEl = document.getElementById('suspendReason');

            if (!reason) {
                reasonEl.classList.add('is-invalid');
                reasonEl.focus();
                return;
            }
            reasonEl.classList.remove('is-invalid');

            const btn = document.getElementById('confirmSuspendBtn');
            setLoading(btn, true, 'Suspend');

            try {
                const data = await postJson('{{ route('admin.violations.suspend', $anggota) }}', { reason });
                bootstrap.Modal.getInstance(document.getElementById('suspendModal')).hide();
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } catch (err) {
                showToast(err.message || 'Gagal suspend anggota.', 'danger');
            } finally {
                setLoading(btn, false, 'Suspend');
            }
        }

        // ── Unsuspend ──────────────────────────────────────────────────
        async function unsuspendMember() {
            if (!confirm('Unsuspend anggota {{ $anggota->nama }}?')) return;

            try {
                const data = await postJson('{{ route('admin.violations.unsuspend', $anggota) }}', {});
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } catch (err) {
                showToast(err.message || 'Gagal unsuspend anggota.', 'danger');
            }
        }

        // ── Add Violation ──────────────────────────────────────────────
        function showAddViolationModal() {
            ['violationType', 'violationCount', 'violationAmount', 'violationDescription']
                .forEach(id => { const el = document.getElementById(id); el.value = ''; el.classList.remove('is-invalid'); });
            new bootstrap.Modal(document.getElementById('addViolationModal')).show();
        }

        async function confirmAddViolation() {
            const type = document.getElementById('violationType').value;
            const count = document.getElementById('violationCount').value;
            const amount = document.getElementById('violationAmount').value;
            const desc = document.getElementById('violationDescription').value;

            if (!type || !count) {
                if (!type) document.getElementById('violationType').classList.add('is-invalid');
                if (!count) document.getElementById('violationCount').classList.add('is-invalid');
                return;
            }

            const btn = document.getElementById('confirmViolationBtn');
            setLoading(btn, true, 'Tambah');

            try {
                const data = await postJson('{{ route('admin.violations.add', $anggota) }}', {
                    type,
                    count: parseInt(count),
                    total_amount: amount ? parseFloat(amount) : null,
                    description: desc,
                });
                bootstrap.Modal.getInstance(document.getElementById('addViolationModal')).hide();
                showToast(data.message, data.auto_suspended ? 'warning' : 'success');
                setTimeout(() => location.reload(), 1200);
            } catch (err) {
                showToast(err.message || 'Gagal menambahkan pelanggaran.', 'danger');
            } finally {
                setLoading(btn, false, 'Tambah');
            }
        }

        // ── Utilities ──────────────────────────────────────────────────
        async function postJson(url, body) {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(body),
            });

            const data = await res.json();

            if (!res.ok || data.status !== 'success') {
                throw new Error(data.message || 'Terjadi kesalahan.');
            }

            return data;
        }

        function setLoading(btn, loading, label) {
            btn.disabled = loading;
            btn.innerHTML = loading
                ? '<span class="spinner-border spinner-border-sm me-1"></span>Memproses...'
                : `<i class="fas fa-${label === 'Suspend' ? 'ban' : 'plus'} me-1"></i>${label}`;
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            if (!container) { alert(message); return; }

            const id = 'toast-' + Date.now();
            container.insertAdjacentHTML('beforeend', `
            <div id="${id}" class="toast align-items-center text-bg-${type} border-0 show mb-2" role="alert">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `);
            setTimeout(() => document.getElementById(id)?.remove(), 4000);
        }
    </script>
@endpush