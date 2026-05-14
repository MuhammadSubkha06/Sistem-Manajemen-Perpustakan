<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if ($this->hasForeign('peminjamans_anggota_id_foreign')) {
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->dropForeign(['anggota_id']);
            });
        }

        Schema::table('peminjamans', function (Blueprint $table) {
            $table->foreign('anggota_id')
                ->references('id')
                ->on('anggotas')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        if ($this->hasForeign('peminjamans_anggota_id_foreign')) {
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->dropForeign(['anggota_id']);
            });
        }

        Schema::table('peminjamans', function (Blueprint $table) {
            $table->foreign('anggota_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    private function hasForeign(string $name): bool
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return false;
        }

        return DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'peminjamans')
            ->where('CONSTRAINT_NAME', $name)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->exists();
    }
};
