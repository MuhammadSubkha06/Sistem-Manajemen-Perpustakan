@extends('layouts.admin')
@section('title', 'Manajemen Pelanggaran Anggota')

@section('content')

    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Manajemen Pelanggaran Anggota</h4>
            <p class="text-muted mb-0">Monitor dan kelola pelanggaran anggota perpustakaan</p>
        </div>

        <div class="d-flex gap-2">
            @foreach(['' => ['label' => 'Semua', 'color' => 'warning'], 'active' => ['label' => 'Aktif', 'color' => 'info'], 'suspended' => ['label' => 'Suspended', 'color' => 'danger']] as $val => $opt)
                <a href="{{ route('admin.violations.index', ['status' => $val]) }}"
                    class="btn btn-sm rounded-2 {{ request('status') === $val ? 'btn-' . $opt['color'] : 'btn-outline-' . $opt['color'] }}">
                    {{ $opt['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="card border-0 rounded-3 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Nama Anggota</th>
                            <th class="px-4 py-3">NIS</th>
                            <th class="px-4 py-3 text-center">Denda</th>
                            <th class="px-4 py-3 text-center">Terlambat</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggotas as $anggota)
                            <tr>
                                <td class="px-4 py-3 fw-semibold">{{ $anggota->nama }}</td>
                                <td class="px-4 py-3 text-muted">{{ $anggota->nis }}</td>

                                <td class="px-4 py-3 text-center">
                                    <span class="badge bg-danger">{{ $anggota->denda_count }}×</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="badge bg-warning text-dark">{{ $anggota->late_return_count }}×</span>
                                </td>

                                <td class="px-4 py-3">
                                    @if($anggota->isSuspended())
                                        <span class="badge bg-danger"><i class="fas fa-ban me-1"></i>Suspended</span>
                                    @elseif($anggota->should_suspend)
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Harus Suspend
                                        </span>
                                    @else
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Aktif</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.violations.show', $anggota) }}"
                                            class="btn btn-sm btn-outline-info rounded-2">
                                            <i class="fas fa-eye me-1"></i>Detail
                                        </a>

                                        @if($anggota->isSuspended())
                                            <button class="btn btn-sm btn-success rounded-2"
                                                onclick="unsuspendMember({{ $anggota->id }}, '{{ e($anggota->nama) }}')">
                                                <i class="fas fa-unlock me-1"></i>Unsuspend
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-danger rounded-2"
                                                onclick="showSuspendModal({{ $anggota->id }}, '{{ e($anggota->nama) }}')">
                                                <i class="fas fa-ban me-1"></i>Suspend
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-5 text-center text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                    Tidak ada data pelanggaran
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-top">
                {{ $anggotas->withQueryString()->links() }}
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
                        Suspend anggota <strong id="memberName"></strong>?
                        Anggota tidak dapat login selama status ini aktif.
                    </p>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Alasan Suspend <span class="text-danger">*</span></label>
                        <textarea class="form-control rounded-2" id="suspendReason" rows="3"
                            placeholder="Masukkan alasan suspend..."></textarea>
                        <div class="invalid-feedback" id="suspendReasonError">Alasan suspend harus diisi.</div>
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

@endsection

@push('scripts')
    <script>
        let currentMemberId = null;

        // ── Suspend ──────────────────────────────────────────────────────
        function showSuspendModal(memberId, memberName) {
            currentMemberId = memberId;
            document.getElementById('memberName').textContent = memberName;
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
            setLoading(btn, true);

            try {
                const data = await postJson(
                    `{{ route('admin.violations.suspend', ':id') }}`.replace(':id', currentMemberId),
                    { reason }
                );
                bootstrap.Modal.getInstance(document.getElementById('suspendModal')).hide();
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } catch (err) {
                showToast(err.message || 'Gagal suspend anggota.', 'danger');
            } finally {
                setLoading(btn, false);
            }
        }

        // ── Unsuspend ────────────────────────────────────────────────────
        async function unsuspendMember(memberId, memberName) {
            if (!confirm(`Unsuspend anggota ${memberName}?`)) return;

            try {
                const data = await postJson(
                    `{{ route('admin.violations.unsuspend', ':id') }}`.replace(':id', memberId),
                    {}
                );
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } catch (err) {
                showToast(err.message || 'Gagal unsuspend anggota.', 'danger');
            }
        }

        // ── Utilities ────────────────────────────────────────────────────
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

        function setLoading(btn, loading) {
            btn.disabled = loading;
            btn.innerHTML = loading
                ? '<span class="spinner-border spinner-border-sm me-1"></span>Memproses...'
                : '<i class="fas fa-ban me-1"></i>Suspend';
        }

        function showToast(message, type = 'success') {
            // Fallback ke alert jika belum ada toast container
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