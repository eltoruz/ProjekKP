# Sistem Manajemen Kerja Sama (SMS)

Aplikasi manajemen kerja sama pemanfaatan data antara Pusdatin Kemendikdasmen dengan mitra (pemerintah daerah, instansi, perguruan tinggi, dll). Mencakup alur pengajuan Nota Kesepakatan, pemilihan & persetujuan data dari kamus metadata, serta pelaporan berkala mitra.

## Tech Stack

- **Framework:** Laravel 13 (PHP 8.3+)
- **Database:** SQL Server via `sqlsrv`
- **Frontend:** Blade + Tailwind CSS CDN + Alpine.js CDN (plugin Collapse)
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

> **Seed metadata:** `MetadataSeeder` mengisi tabel `metadata` (kamus kolom/tabel data) dari file `storage/app/metadata_seed.json`. Bila file tersebut tidak ada, seeding metadata dilewati tanpa error. `MasterDataSeeder` mengisi data referensi (jenis, tingkat, metode, status, implementasi).

## Role

Autentikasi tidak dipakai pada fase ini — akses dibedakan lewat prefix URL. Middleware `AutoLoginAdmin` otomatis membuat & login user admin (id=1) saat mengakses area `/admin`.

| Role | URL | Deskripsi |
|------|-----|-----------|
| **Mitra** | `/mitra` | Mengajukan Nota Kesepakatan, upload dokumen & undangan, memilih data, lihat progress, upload laporan berkala |
| **Admin** | `/admin` | Review pengajuan, jadwalkan, finalisasi, setujui pemilihan data per item, kelola implementasi |

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

Admin dapat menolak kapan saja → status kembali null (Draft/Ditolak).
```

## Alur Pemilihan & Persetujuan Data

Aktif setelah Nota Kesepakatan berstatus **Selesai (Status 5)**.

```
1. Mitra pilih kolom/tabel dari kamus metadata + isi alasan
   → Simpan Draf (status_pemilihan_data = draft)

2. Mitra Ajukan Pemilihan Data → status_pemilihan_data = submitted
   (terkunci / read-only bagi mitra)

3. Admin buka "Kelola Persetujuan Data per Item"
   → Setujui / Tolak / Pending per kolom + catatan admin
   → Set Metode Pertukaran & Status Implementasi
   → Simpan → diarahkan ke halaman Review + ringkasan keputusan

4. Admin dapat "Buka Kunci Form Mitra" → status_pemilihan_data kembali draft
   (keputusan approve/reject item yang tidak berubah tetap dipertahankan)
```

## Alur Pelaporan Berkala Mitra

Menu pelaporan aktif bila **Status Implementasi = 3 (Aktif)** DAN minimal **1 item data disetujui** admin. Mitra mengunggah laporan per periode (Semester 1 / Semester 2) beserta tahun; tersimpan di tabel `mitra_reports`.

## Diskusi (Chat)

Setiap kerja sama memiliki widget diskusi Mitra ↔ Admin (tabel `kerjasama_chats`). Endpoint chat dibatasi `throttle:30,1` (30 request/menit).

## Struktur Status Dokumen (`ks_status_dok`)

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
| `metadata` | Kamus metadata: daftar database/tabel/kolom data yang dapat dipilih |
| `metadata_user` | Pilihan data mitra per kerja sama + status persetujuan admin |
| `mitra_reports` | Laporan berkala yang diunggah mitra |
| `kerjasama_chats` | Pesan diskusi Mitra ↔ Admin per kerja sama |

## Folder Dokumen

- `folder_ks` — JSON array: Surat Permohonan [0], Draft NK [1], Surat Undangan [2]
- `dokumen_ks` — Dokumen final TTD (string, hanya admin)
- `dokumen_pendukung` — Dokumen pendukung (admin input)
- Laporan berkala disimpan di `laporan/{kerjasama_id}/` pada public disk

## Catatan Status Pemilihan Data (`status_pemilihan_data`)

| Nilai | Arti |
|-------|------|
| `draft` | Mitra masih menyusun / dibuka kunci oleh admin |
| `submitted` | Diajukan final oleh mitra, terkunci untuk revisi |

Status persetujuan per item (`metadata_user.approval_status`): `pending`, `approved`, `rejected`.

## License

Proprietary — internal Kemendikdasmen.
