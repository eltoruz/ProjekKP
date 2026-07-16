<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ImportExistingKerjasamaSeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('List Tabel Kerjasama.xlsx');

        if (!file_exists($path)) {
            $this->command?->warn('XLSX file not found, skipping import.');
            return;
        }

        $imported = 0;
        $handle = fopen($path, 'rb');
        $contents = fread($handle, filesize($path));
        fclose($handle);

        $tmpFile = tempnam(sys_get_temp_dir(), 'xlsx') . '.xlsx';
        file_put_contents($tmpFile, $contents);

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        try {
            $spreadsheet = $reader->load($tmpFile);
        } catch (\Exception $e) {
            $this->command?->error('Failed to read XLSX: ' . $e->getMessage());
            @unlink($tmpFile);
            return;
        }
        @unlink($tmpFile);

        $worksheet = $spreadsheet->getSheetByName('TO DB FIX');
        if (!$worksheet) {
            $this->command?->warn('Sheet "TO DB FIX" not found.');
            return;
        }

        $rows = $worksheet->toArray();
        $dataRows = array_slice($rows, 1);

        foreach ($dataRows as $row) {
            $noInput = $this->safeStr($row[1] ?? null);
            $namaKl = $this->safeStr($row[10] ?? null);

            if (empty($noInput) && empty($namaKl)) {
                continue;
            }

            $ksStatusDok = $this->safeInt($row[24] ?? null);
            $statusPengajuan = $this->mapStatus($ksStatusDok);

            try {
                // Skip if already imported
                if ($noInput && DB::table('kerjasama')->where('no_input', $noInput)->exists()) {
                    continue;
                }

                DB::table('kerjasama')->insert([
                    'kerjasama_id' => (string) Str::uuid(),
                    'no_input' => $this->safeStr($row[1] ?? null),
                    'ks_jenis' => $this->safeInt($row[2] ?? null),
                    'ks_tingkat' => $this->safeInt($row[4] ?? null),
                    'kode_wilayah' => $this->safeStr($row[8] ?? null),
                    'provinsi' => $this->safeStr($row[6] ?? null),
                    'nama_kl' => $this->safeStr($row[10] ?? null),
                    'jumlah_kl_terlibat' => $this->safeInt($row[9] ?? null),
                    'pihak1' => $this->safeStr($row[11] ?? null),
                    'pihak2' => $this->safeStr($row[12] ?? null),
                    'tentang' => $this->safeStr($row[13] ?? null),
                    'jangka_waktu_thn' => $this->safeInt($row[14] ?? null),
                    'tanggal_mulai_ks' => $this->safeDate($row[15] ?? null),
                    'tanggal_selesai_ks' => $this->safeDate($row[16] ?? null),
                    'sisa_masa_berlaku' => $this->safeStr($row[17] ?? null),
                    'tahun_mulai' => $this->safeInt($row[18] ?? null),
                    'tanggal_pembahasan_ks' => $this->safeDate($row[19] ?? null),
                    'nomor_pihak1' => $this->safeStr($row[20] ?? null),
                    'nomor_pihak2' => $this->safeStr($row[21] ?? null),
                    'ttd_pihak1' => $this->safeStr($row[22] ?? null),
                    'ttd_pihak2' => $this->safeStr($row[23] ?? null),
                    'ks_status_dok' => $ksStatusDok,
                    'narahubung_adm' => $this->safeStr($row[26] ?? null),
                    'nomor_cp_adm' => $this->safeStr($row[27] ?? null),
                    'ks_metode' => $this->safeInt($row[28] ?? null),
                    'ks_implementasi' => $this->safeInt($row[30] ?? null),
                    'narahubung_teknis' => $this->safeStr($row[32] ?? null),
                    'nomor_cp_teknis' => $this->safeStr($row[33] ?? null),
                    'dokumen_ks' => $this->safeStr($row[34] ?? null),
                    'dokumen_pendukung' => $this->safeStr($row[35] ?? null),
                    'folder_ks' => $this->safeStr($row[36] ?? null),
                    'unit_utama_terlibat' => $this->safeStr($row[37] ?? null),
                    'pusdatin_kirim_data' => $this->safeStr($row[38] ?? null),
                    'pusdatin_terima_data' => $this->safeStr($row[39] ?? null),
                    'create_date' => $this->safeDate($row[40] ?? null) ?? now(),
                    'last_update' => $this->safeDate($row[41] ?? null) ?? now(),
                    'expired_date' => $this->safeDate($row[42] ?? null),
                    'soft_delete' => false,
                    'status_pengajuan' => $statusPengajuan,
                    'review_log' => json_encode([[
                        'status' => $statusPengajuan,
                        'alasan' => 'Import dari data existing Pusdatin',
                        'waktu' => now()->toDateTimeString(),
                    ]]),
                ]);
                $imported++;
            } catch (\Exception $e) {
                $this->command?->warn("Skipping row ({$noInput}): " . $e->getMessage());
            }
        }

        $this->command?->info("Imported {$imported} of " . count($dataRows) . " rows from XLSX.");
    }

    private function safeStr($val): ?string
    {
        if ($val === null) return null;
        if (is_numeric($val) && !is_string($val)) $val = (string) $val;
        $str = trim((string) $val);
        if ($str === '' || $str === '-' || $str === '?' || $str === 'None' || $str === '#N/A' || $str === 'NULL' || $str === 'null') {
            return null;
        }
        return $str;
    }

    private function safeInt($val): ?int
    {
        $str = $this->safeStr($val);
        if ($str === null) return null;
        if (is_numeric($str)) return (int) $str;
        return null;
    }

    private function safeDate($val): ?string
    {
        $str = $this->safeStr($val);
        if ($str === null) return null;

        // Handle PhpSpreadsheet returning float timestamp (excel serial date)
        if (is_numeric($val) && $val > 10000) {
            // Could be Excel serial date
            try {
                $d = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $val);
                return $d->format('Y-m-d H:i:s');
            } catch (\Exception $e) {}
        }

        // Try parse string date
        $formats = ['Y-m-d H:i:s', 'Y-m-d\TH:i:s', 'Y-m-d', 'd/m/Y', 'Y'];
        foreach ($formats as $fmt) {
            try {
                $d = Carbon::createFromFormat($fmt, $str);
                if ($d && $d->year > 2000) {
                    return $d->format('Y-m-d H:i:s');
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return null;
    }

    private function mapStatus(?int $ksStatusDok): string
    {
        return match($ksStatusDok) {
            2 => 'DISETUJUI',
            3 => 'MENUNGGU_PEMBAHASAN',
            4 => 'SELESAI_PEMBAHASAN',
            5 => 'SELESAI',
            default => 'DRAFT',
        };
    }
}
