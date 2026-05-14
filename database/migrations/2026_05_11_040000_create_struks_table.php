<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('struks', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->foreignId('anggota_id')->constrained('anggotas')->cascadeOnDelete();
            $table->foreignId('peminjaman_id')->constrained('peminjamans')->cascadeOnDelete();
            $table->string('jenis');
            $table->string('judul');
            $table->unsignedInteger('nominal')->default(0);
            $table->json('payload')->nullable();
            $table->timestamp('issued_at');
            $table->timestamps();

            $table->unique(['peminjaman_id', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('struks');
    }
};
