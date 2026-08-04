<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kerjasama', function (Blueprint $table) {
            $table->uuid('kerjasama_id')->primary();
            $table->string('no_input', 50)->nullable();
            $table->foreignId('ks_jenis')->constrained('ks_jenis');
            $table->foreignId('ks_tingkat')->constrained('ks_tingkat');
            $table->string('kode_wilayah', 20)->nullable();
            $table->string('nama_kl', 200)->nullable();
            $table->integer('jumlah_kl_terlibat')->default(1);
            $table->string('pihak1', 200)->nullable();
            $table->string('pihak2', 200)->nullable();
            $table->text('tentang')->nullable();
            $table->integer('jangka_waktu_thn')->nullable();
            $table->date('tanggal_mulai_ks')->nullable();
            $table->date('tanggal_selesai_ks')->nullable();
            $table->string('nomor_pihak1', 100)->nullable();
            $table->string('nomor_pihak2', 100)->nullable();
            $table->string('ttd_pihak1', 200)->nullable();
            $table->string('ttd_pihak2', 200)->nullable();
            $table->foreignId('ks_status_dok')->nullable()->constrained('ks_status_dok');
            $table->string('narahubung_adm', 200)->nullable();
            $table->string('nomor_cp_adm', 50)->nullable();
            $table->foreignId('ks_metode')->nullable()->constrained('ks_metode');
            $table->foreignId('ks_implementasi')->nullable()->constrained('ks_implementasi');
            $table->string('narahubung_teknis', 200)->nullable();
            $table->string('nomor_cp_teknis', 50)->nullable();
            $table->text('dokumen_ks')->nullable();
            $table->text('dokumen_pendukung')->nullable();
            $table->text('folder_ks')->nullable();
            $table->text('unit_utama_terlibat')->nullable();
            $table->text('pusdatin_kirim_data')->nullable();
            $table->text('pusdatin_terima_data')->nullable();
            $table->boolean('soft_delete')->default(false);
            $table->timestamp('create_date')->nullable();
            $table->timestamp('last_update')->nullable();
            $table->dateTime('tanggal_pembahasan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kerjasama');
    }
};
