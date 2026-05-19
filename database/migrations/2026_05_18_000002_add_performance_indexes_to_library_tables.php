<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->index(['anggota_id', 'status', 'approval_status'], 'peminjamans_member_active_idx');
            $table->index(['buku_id', 'status', 'approval_status'], 'peminjamans_book_active_idx');
            $table->index(['approval_status', 'status'], 'peminjamans_approval_status_idx');
            $table->index(['return_status', 'status'], 'peminjamans_return_status_idx');
            $table->index('tgl_pinjam', 'peminjamans_tgl_pinjam_idx');
            $table->index('tgl_kembali_rencana', 'peminjamans_due_date_idx');
        });

        Schema::table('struks', function (Blueprint $table) {
            $table->index(['anggota_id', 'is_approved', 'issued_at'], 'struks_member_approved_idx');
            $table->index(['is_approved', 'issued_at'], 'struks_approved_issued_idx');
        });
    }

    public function down(): void
    {
        Schema::table('struks', function (Blueprint $table) {
            $table->dropIndex('struks_member_approved_idx');
            $table->dropIndex('struks_approved_issued_idx');
        });

        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropIndex('peminjamans_member_active_idx');
            $table->dropIndex('peminjamans_book_active_idx');
            $table->dropIndex('peminjamans_approval_status_idx');
            $table->dropIndex('peminjamans_return_status_idx');
            $table->dropIndex('peminjamans_tgl_pinjam_idx');
            $table->dropIndex('peminjamans_due_date_idx');
        });
    }
};
