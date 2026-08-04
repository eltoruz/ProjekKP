<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kerjasama', function (Blueprint $table) {
            if (!Schema::hasColumn('kerjasama', 'status_pemilihan_data')) {
                $table->string('status_pemilihan_data', 20)->default('draft')->nullable();
            }
        });

        Schema::table('metadata_user', function (Blueprint $table) {
            if (!Schema::hasColumn('metadata_user', 'approval_status')) {
                $table->string('approval_status', 20)->default('pending')->nullable();
            }
            if (!Schema::hasColumn('metadata_user', 'catatan_admin')) {
                $table->text('catatan_admin')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('kerjasama', function (Blueprint $table) {
            if (Schema::hasColumn('kerjasama', 'status_pemilihan_data')) {
                $table->dropColumn('status_pemilihan_data');
            }
        });

        Schema::table('metadata_user', function (Blueprint $table) {
            if (Schema::hasColumn('metadata_user', 'approval_status')) {
                $table->dropColumn('approval_status');
            }
            if (Schema::hasColumn('metadata_user', 'catatan_admin')) {
                $table->dropColumn('catatan_admin');
            }
        });
    }
};
