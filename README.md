# Sistem Informasi Kerja Sama (SIKS)

Aplikasi manajemen kerja sama antara Kemendikdasmen dengan mitra (pemerintah daerah, instansi, perguruan tinggi, dll).

## Tech Stack

- **Framework:** Laravel 13 (PHP 8.3+)
- **Admin Panel:** Filament 3
- **Database:** SQL Server (via `sqlsrv`)
- **Frontend:** Blade + Tailwind CSS + Alpine.js
- **Storage:** Local (public disk)

## Prasyarat

- PHP 8.3+
- Composer
- Node.js 18+ (untuk build asset)
- SQL Server (2017+) dengan driver `sqlsrv` PHP
- ODBC Driver 18 for SQL Server

## Instalasi

```bash
# 1. Clone repository
git clone <repo-url>
cd ProjekKP

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env

# 4. Konfigurasi database di .env
# DB_CONNECTION=sqlsrv
# DB_HOST=127.0.0.1
# DB_PORT=1433
# DB_DATABASE=kerjasama_backbone
# DB_USERNAME=
# DB_PASSWORD=

# 5. Generate app key
php artisan key:generate

# 6. Migrasi + seed data master
php artisan migrate --force
php artisan db:seed --class=MasterDataSeeder

# 7. Link storage
php artisan storage:link

# 8. Build frontend (opsional, untuk development)
npm run build
```

## Menjalankan

```bash
# Development server
php artisan serve

# Admin panel → http://localhost:8000/admin
# Mitra panel → http://localhost:8000/mitra
```

## Role

| Role | URL | Deskripsi |
|------|-----|-----------|
| **Mitra** | `/mitra` | Mengajukan kerja sama, upload dokumen, lihat progress |
| **Admin** | `/admin` | Review pengajuan, jadwalkan, finalisasi. Berbasis Filament |

## Alur Kerja Sama (Nota Kesepakatan)

```
Mitra Create (Draft)
  → Ajukan ke Admin → Status 1 (Mengirimkan surat permohonan)
    → Admin Setujui → Status 2 (Menunggu surat undangan)
      → Mitra Upload Undangan
        → Admin Jadwalkan → Status 3 (Dokumen dalam proses pembahasan)
          → Admin Edit Manual → Status 4 (Penandatanganan)
            → Admin Finalisasi → Status 5 (Selesai)

Admin dapat menolak kapan saja → status kembali Draft.
Admin juga dapat mengedit status secara manual via halaman Edit.
```

## Struktur Status

| ID | Nama Status |
|----|-------------|
| 1 | Mengirimkan surat permohonan |
| 2 | Pemohon menyampaikan undangan pembahasan NK/PKS/NDA |
| 3 | Dokumen dalam proses pembahasan |
| 4 | Dokumen dalam proses penandatanganan |
| 5 | Dokumen telah ditandatangani dan diterima oleh masing-masing Pihak |
| 6 | Masa berlaku selesai (cron job) |

## License

Proprietary — internal Kemendikdasmen.
