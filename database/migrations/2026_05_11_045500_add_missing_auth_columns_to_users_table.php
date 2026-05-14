<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }

            if (!Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken()->after('password');
            }
        });

        if (Schema::hasColumn('users', 'role_id') && DB::connection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE users ALTER role_id SET DEFAULT 2');
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'remember_token')) {
                $table->dropRememberToken();
            }

            if (Schema::hasColumn('users', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
        });
    }
};
