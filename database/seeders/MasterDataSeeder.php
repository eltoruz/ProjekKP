<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ks_jenis')->insert([
            ['nama_jenis' => 'Nota Kesepahaman', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis' => 'Perjanjian Kerja Sama', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis' => 'Nota Kesepakatan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis' => 'Perjanjian Kerahasiaan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis' => 'Petunjuk Teknis', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis' => 'Adendum', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis' => 'Komitmen Bersama', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('ks_tingkat')->insert([
            ['nama_tingkat' => 'Kementerian/Lembaga Pemerintahan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_tingkat' => 'Unit Kerja Kemendikdasmen', 'created_at' => now(), 'updated_at' => now()],
            ['nama_tingkat' => 'Pemerintah Daerah', 'created_at' => now(), 'updated_at' => now()],
            ['nama_tingkat' => 'Perguruan Tinggi', 'created_at' => now(), 'updated_at' => now()],
            ['nama_tingkat' => 'Lembaga Swasta', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('ks_metode')->insert([
            ['nama_metode' => 'API', 'created_at' => now(), 'updated_at' => now()],
            ['nama_metode' => 'SSIS', 'created_at' => now(), 'updated_at' => now()],
            ['nama_metode' => 'Excel', 'created_at' => now(), 'updated_at' => now()],
            ['nama_metode' => 'Belum ditentukan', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('ks_status_dok')->insert([
            ['nama_status' => 'Mengirimkan surat permohonan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'Pemohon menyampaikan undangan pembahasan NK/PKS/NDA', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'Dokumen dalam proses pembahasan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'Dokumen dalam proses penandatanganan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'Dokumen telah ditandatangani dan diterima oleh masing-masing Pihak', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'masa berlaku selesai', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('ks_implementasi')->insert([
            ['nama_status' => 'Belum Aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'Koordinasi Teknis Pengaktifan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'Aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'Tidak Aktif', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
