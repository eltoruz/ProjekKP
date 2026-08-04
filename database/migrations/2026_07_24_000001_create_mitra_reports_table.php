<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mitra_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('kerjasama_id');
            $table->integer('tahun');
            $table->string('periode', 50); // e.g. "Semester 1", "Semester 2"
            $table->string('file_path', 500);
            $table->string('nama_file', 255);
            $table->text('catatan')->nullable();
            $table->string('status', 20)->default('dikirim');
            $table->timestamps();

            $table->foreign('kerjasama_id')->references('kerjasama_id')->on('kerjasama')->onDelete('cascade');
            $table->index(['kerjasama_id', 'tahun', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitra_reports');
    }
};
