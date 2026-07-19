<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kerjasama', function ($table) {
            $table->dropColumn('tanggal_pembahasan_ks');
        });
        Schema::table('kerjasama', function ($table) {
            $table->dateTime('tanggal_pembahasan')->nullable()->after('last_update');
        });
    }

    public function down(): void
    {
        Schema::table('kerjasama', function ($table) {
            $table->dropColumn('tanggal_pembahasan');
        });
        Schema::table('kerjasama', function ($table) {
            $table->date('tanggal_pembahasan_ks')->nullable()->after('last_update');
        });
    }
};
