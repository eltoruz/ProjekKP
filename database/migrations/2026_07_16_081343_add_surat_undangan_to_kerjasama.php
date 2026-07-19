<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kerjasama', function (Blueprint $table) {
            $table->string('surat_undangan')->nullable()->after('dokumen_ks');
        });
    }

    public function down(): void
    {
        Schema::table('kerjasama', function (Blueprint $table) {
            $table->dropColumn('surat_undangan');
        });
    }
};
