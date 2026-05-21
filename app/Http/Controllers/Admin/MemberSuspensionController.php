<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Violation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberSuspensionController extends Controller
{
    // ─── Index ─────────────────────────────────────────────────

    /**
     * Daftar anggota beserta statistik pelanggaran.
     */
    public function violations(Request $request): View
    {
        $query = Anggota::with('violations', 'user');

        match ($request->get('status')) {
            'suspended' => $query->whereNotNull('suspended_at'),
            'active'    => $query->whereNull('suspended_at'),
            default     => null,
        };

        $anggotas = $query->paginate(20)->through(function (Anggota $anggota) {
            $anggota->denda_count      = $anggota->getDendaCount();
            $anggota->late_return_count = $anggota->getLateReturnCount();
            $anggota->should_suspend   = $anggota->shouldBeSuspended();
            return $anggota;
        });

        return view('admin.members.violations', compact('anggotas'));
    }

    // ─── Show ───────────────────────────────────────────────────

    /**
     * Detail pelanggaran satu anggota.
     */
    public function show(Anggota $anggota): View
    {
        $violations = $anggota->violations()->latest()->paginate(20);

        return view('admin.members.violation-detail', compact('anggota', 'violations'));
    }

    // ─── Suspend / Unsuspend ────────────────────────────────────

    /**
     * Suspend anggota secara manual.
     */
    public function suspend(Request $request, Anggota $anggota): JsonResponse
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        if ($anggota->isSuspended()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anggota sudah dalam status suspended.',
            ], 422);
        }

        $anggota->suspend($request->reason);

        return response()->json([
            'status'  => 'success',
            'message' => "Anggota {$anggota->nama} berhasil di-suspend.",
        ]);
    }

    /**
     * Cabut status suspend anggota.
     */
    public function unsuspend(Anggota $anggota): JsonResponse
    {
        if (! $anggota->isSuspended()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anggota tidak dalam status suspended.',
            ], 422);
        }

        $anggota->unsuspend();

        return response()->json([
            'status'  => 'success',
            'message' => "Anggota {$anggota->nama} berhasil di-unsuspend.",
        ]);
    }

    // ─── Violations ─────────────────────────────────────────────

    /**
     * Tambah atau akumulasi pelanggaran pada anggota.
     */
    public function addViolation(Request $request, Anggota $anggota): JsonResponse
    {
        $data = $request->validate([
            'type'         => 'required|in:denda,late_return,damage',
            'count'        => 'required|integer|min:1',
            'total_amount' => 'nullable|numeric|min:0',
            'description'  => 'nullable|string|max:500',
        ]);

        // Akumulasi jika record sudah ada, buat baru jika belum
        $violation = Violation::firstOrNew([
            'anggota_id' => $anggota->id,
            'type'       => $data['type'],
        ]);

        $violation->count        = ($violation->count ?? 0) + $data['count'];
        $violation->total_amount = ($violation->total_amount ?? 0) + ($data['total_amount'] ?? 0);

        // Simpan description hanya jika diberikan atau record baru
        if (! empty($data['description'])) {
            $violation->description = $data['description'];
        }

        $violation->save();

        // Auto-suspend jika sudah melewati batas
        $autoSuspended = false;
        if (! $anggota->isSuspended() && $anggota->shouldBeSuspended()) {
            $anggota->suspend(
                'Otomatis di-suspend karena pelanggaran: ' . $violation->getTypeLabel()
            );
            $autoSuspended = true;
        }

        $message = $autoSuspended
            ? 'Pelanggaran ditambahkan. Anggota otomatis di-suspend karena telah melampaui batas maksimal pelanggaran.'
            : 'Pelanggaran berhasil ditambahkan.';

        return response()->json([
            'status'         => 'success',
            'message'        => $message,
            'auto_suspended' => $autoSuspended,
            'violation'      => $violation,
        ]);
    }

    // ─── Notification ───────────────────────────────────────────

    /**
     * Tandai notifikasi pelanggaran sudah dibaca oleh admin.
     */
    public function updateNotificationStatus(Request $request, Anggota $anggota): JsonResponse
    {
        $request->validate([
            'notification_read' => 'required|boolean',
        ]);

        // Placeholder – implementasi sesuai sistem notifikasi yang dipakai.

        return response()->json([
            'status'  => 'success',
            'message' => 'Status notifikasi diperbarui.',
        ]);
    }
}