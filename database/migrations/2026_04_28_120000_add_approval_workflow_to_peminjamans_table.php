<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->string('approval_status')->default('approved')->after('status');
            $table->text('approval_note')->nullable()->after('approval_status');
            $table->timestamp('approved_at')->nullable()->after('approval_note');
            $table->string('return_status')->default('none')->after('approved_at');
            $table->text('return_note')->nullable()->after('return_status');
            $table->timestamp('return_requested_at')->nullable()->after('return_note');
            $table->timestamp('return_processed_at')->nullable()->after('return_requested_at');
        });
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn([
                'approval_status',
                'approval_note',
                'approved_at',
                'return_status',
                'return_note',
                'return_requested_at',
                'return_processed_at',
            ]);
        });
    }
};
