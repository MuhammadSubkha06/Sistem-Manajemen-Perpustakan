<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggotas')->cascadeOnDelete();
            $table->enum('type', ['denda', 'late_return', 'damage'])->default('denda');
            $table->integer('count')->default(1);
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index(['anggota_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violations');
    }
};
