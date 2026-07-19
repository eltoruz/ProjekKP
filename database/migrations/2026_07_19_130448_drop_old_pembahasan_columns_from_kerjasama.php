<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kerjasama', function (Blueprint $table) {
            $table->dropColumn(['jam_pembahasan', 'lokasi_pembahasan', 'link_meeting', 'pic_pembahasan']);
        });
    }

    public function down(): void
    {
        Schema::table('kerjasama', function (Blueprint $table) {
            $table->string('jam_pembahasan')->nullable();
            $table->string('lokasi_pembahasan')->nullable();
            $table->string('link_meeting')->nullable();
            $table->string('pic_pembahasan')->nullable();
        });
    }
};
