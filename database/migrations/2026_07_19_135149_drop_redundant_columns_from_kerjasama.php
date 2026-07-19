<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kerjasama', function (Blueprint $table) {
            $table->dropColumn(['sisa_masa_berlaku', 'status_pengajuan']);
        });
    }

    public function down(): void
    {
        Schema::table('kerjasama', function (Blueprint $table) {
            $table->string('sisa_masa_berlaku', 100)->nullable();
            $table->string('status_pengajuan', 50)->default('DRAFT');
        });
    }
};
