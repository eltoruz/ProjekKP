# Sistem Manajemen Kerja Sama (SMS)

Aplikasi manajemen kerja sama antara Pusdatin Kemendikdasmen dengan mitra (pemerintah daerah, instansi, perguruan tinggi, dll).

## Tech Stack

- **Framework:** Laravel 13 (PHP 8.3+)
- **Database:** SQL Server via `sqlsrv`
- **Frontend:** Blade + Tailwind CSS CDN + Alpine.js CDN
- **Storage:** Local (public disk)

## Prasyarat

- PHP 8.3+
- Composer
- SQL Server (2017+) dengan driver `sqlsrv` PHP
- ODBC Driver 18 for SQL Server

## Instalasi

```bash
git clone <repo-url>
cd ProjekKP

composer install
cp .env.example .env
# sesuaikan DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD

php artisan key:generate
php artisan storage:link
php artisan migrate:fresh --seed

php artisan serve
```

## Role

| Role | URL | Deskripsi |
|------|-----|-----------|
| **Mitra** | `/mitra` | Mengajukan Nota Kesepakatan, upload dokumen, upload undangan, lihat progress |
| **Admin** | `/admin` | Review pengajuan, jadwalkan, input data arsip, finalisasi |

## Alur Nota Kesepakatan

```
1. Mitra create (Draft)
   → Upload Surat Permohonan + Draft NK
   → Ajukan ke Admin → Status 1

2. Admin Setujui & Jadwalkan → Status 2
   (modal isi tanggal/waktu pembahasan)

3. Mitra upload Surat Undangan → Auto Status 3

4. Admin Lanjut ke Penandatanganan → Status 4

5. Admin Finalisasi → Status 5 (Selesai)
   (isi data pihak, perihal, jangka, tanggal, TTD, dll)

Admin dapat menolak kapan saja (status 1) → kembali Draft.
```

## Struktur Status

| ID | Nama Status |
|----|-------------|
| 1 | Mengirimkan surat permohonan |
| 2 | Pemohon menyampaikan undangan pembahasan NK/PKS/NDA |
| 3 | Dokumen dalam proses pembahasan |
| 4 | Dokumen dalam proses penandatanganan |
| 5 | Dokumen telah ditandatangani dan diterima oleh masing-masing Pihak |
| 6 | Masa berlaku selesai |

## Struktur Tabel

| Tabel | Deskripsi |
|-------|-----------|
| `users` | User admin (auto-create via middleware) |
| `ks_jenis` | Jenis kerja sama |
| `ks_tingkat` | Tingkat mitra |
| `ks_metode` | Metode pertukaran data |
| `ks_status_dok` | Status dokumen |
| `ks_implementasi` | Status implementasi |
| `kerjasama` | Data utama kerja sama |
| `review_logs` | Log aktivitas review (timeline) |

## Folder Dokumen

- `folder_ks` — JSON array: Surat Permohonan [0], Draft NK [1], Surat Undangan [2]
- `dokumen_ks` — Dokumen final TTD (string, hanya admin)
- `dokumen_pendukung` — Dokumen pendukung (admin input)

## License

Proprietary — internal Kemendikdasmen.
