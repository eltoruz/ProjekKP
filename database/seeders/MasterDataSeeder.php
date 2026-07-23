<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $jenis = [
            1 => 'Nota Kesepahaman',
            2 => 'Perjanjian Kerja Sama',
            3 => 'Nota Kesepakatan',
            4 => 'Perjanjian Kerahasiaan',
            5 => 'Petunjuk Teknis',
            6 => 'Adendum',
            7 => 'Komitmen Bersama',
        ];
        $this->seedTable('ks_jenis', 'nama_jenis', $jenis);

        $tingkat = [
            1 => 'Kementerian/Lembaga Pemerintahan',
            2 => 'Unit Kerja Kemendikdasmen',
            3 => 'Pemerintah Daerah',
            4 => 'Perguruan Tinggi',
            5 => 'Lembaga Swasta',
        ];
        $this->seedTable('ks_tingkat', 'nama_tingkat', $tingkat);

        $metode = [
            1 => 'API',
            2 => 'SSIS',
            3 => 'Excel',
            4 => 'Belum ditentukan',
        ];
        $this->seedTable('ks_metode', 'nama_metode', $metode);

        $statusDok = [
            1 => 'Mengirimkan surat permohonan',
            2 => 'Pemohon menyampaikan undangan pembahasan NK/PKS/NDA',
            3 => 'Dokumen dalam proses pembahasan',
            4 => 'Dokumen dalam proses penandatanganan',
            5 => 'Dokumen telah ditandatangani dan diterima oleh masing-masing Pihak',
            6 => 'masa berlaku selesai',
        ];
        $this->seedTable('ks_status_dok', 'nama_status', $statusDok);

        $implementasi = [
            1 => 'Belum Aktif',
            2 => 'Koordinasi Teknis Pengaktifan',
            3 => 'Aktif',
            4 => 'Tidak Aktif',
        ];
        $this->seedTable('ks_implementasi', 'nama_status', $implementasi);
    }

    private function seedTable(string $table, string $column, array $data): void
    {
        $isSqlSrv = DB::getDriverName() === 'sqlsrv';

        if ($isSqlSrv) {
            $now = now()->format('Y-m-d H:i:s.v');
            $statements = ["SET IDENTITY_INSERT {$table} ON;"];

            foreach ($data as $id => $nama) {
                $escapedNama = str_replace("'", "''", $nama);
                $statements[] = "IF EXISTS (SELECT 1 FROM {$table} WHERE id = {$id}) " .
                    "UPDATE {$table} SET {$column} = '{$escapedNama}', updated_at = '{$now}' WHERE id = {$id}; " .
                    "ELSE " .
                    "INSERT INTO {$table} (id, {$column}, created_at, updated_at) VALUES ({$id}, '{$escapedNama}', '{$now}', '{$now}');";
            }

            $statements[] = "SET IDENTITY_INSERT {$table} OFF;";

            DB::unprepared(implode("\n", $statements));
        } else {
            foreach ($data as $id => $nama) {
                DB::table($table)->updateOrInsert(
                    ['id' => $id],
                    [$column => $nama, 'updated_at' => now(), 'created_at' => now()]
                );
            }
        }

        DB::table($table)->whereNotIn('id', array_keys($data))->delete();
    }
}
