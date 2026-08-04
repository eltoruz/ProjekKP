# Design Spec: Penyesuaian Date Picker & Pelaporan Berkala (Tengah Tahun & Akhir Tahun)

## Overview
Peningkatan pada dua bagian utama aplikasi:
1. **Input Jadwal (Date Picker Component)**: Menambahkan pemilih tahun (year picker/dropdown) pada komponen `x-date-picker` untuk mempermudah penentuan tanggal lintas rentang tahun.
2. **Halaman Pelaporan Berkala Mitra**:
   - Menghapus semua penggunaan kata "Semester" dan menggantinya dengan "Tengah Tahun" dan "Akhir Tahun".
   - Membuat kartu pelaporan berbasis jangka waktu tahun (misal jangka waktu 4 tahun = 4 kartu tahun).
   - Setiap kartu tahun memiliki dua tombol aksi minimalis: "Laporan Tengah Tahun" dan "Laporan Akhir Tahun" tanpa pembatasan deadline waktu pengisian.
   - Form **Laporan Tengah Tahun**: Modal bergaya *Google Form* berisi 3 bidang role ("Peran 1", "Peran 2", "Peran 3") sebagai tampilan UI murni.
   - Form **Laporan Akhir Tahun**: Modal upload dokumen sederhana (hanya input file PDF).
   - Menghindari emoji bergaya AI generic, menggunakan SVG minimalis yang bersih.

---

## 1. Peningkatan Date Picker (`resources/views/components/date-picker.blade.php` & `resources/views/layouts/app.blade.php`)

### Desain UI/UX:
- Di bagian header kalender `date-picker`, di samping label bulan dan tombol navigasi panah, tambahkan pemilih tahun berupa `<select>` dropdown.
- Opsi tahun membentang dari `viewY - 5` hingga `viewY + 10` (atau rentang dinamis 2020 hingga 2035).
- Memilih tahun di dropdown secara langsung memperbarui `viewY` di Alpine state (`ksDatePicker`), sehingga grid kalender merender bulan dan tahun tersebut secara instan.

### State Component (`ksDatePicker`):
- `yearOptions`: Getter array tahun dari `currentYear - 5` hingga `currentYear + 10`.
- Event handler `changeYear(y)` yang mengubah `viewY = parseInt(y)`.

---

## 2. Struktur Kartu & Periode Pelaporan (`LaporanController.php` & `laporan.blade.php`)

### Backend (`app/Http/Controllers/Mitra/LaporanController.php`):
- Mengganti pemetaan periode dari `"Semester 1"` & `"Semester 2"` menjadi `"Tengah Tahun"` & `"Akhir Tahun"`.
- Method `susunPeriodeLaporan`:
  - Menghitung jumlah tahun sesuai `jangka_waktu_thn`.
  - Mengelompokkan kartu berdasarkan tahun (`Tahun ke-1 (2026)`, `Tahun ke-2 (2027)`, dst.).
  - Setiap kartu tahun berisi state untuk dua periode: `Tengah Tahun` dan `Akhir Tahun`.
  - Tidak ada penguncian deadline ("tanpa deadline pengisian").
- Validasi pada method `store`:
  - `periode` divalidasi: `in:Tengah Tahun,Akhir Tahun`.
  - Opsional penyimpanan data peran untuk `Tengah Tahun` jika dikirimkan dari UI.

### Frontend (`resources/views/mitra/kerjasama/laporan.blade.php`):
- **Kartu Tahun**:
  - Merender kartu untuk setiap tahun kerja sama (1 .. N tahun).
  - Judul Kartu: `Tahun ke-[X] ([Tahun])`.
  - Menampilkan dua tombol aksi utama dengan ikon SVG minimalis:
    - **Laporan Tengah Tahun**: Membuka Modal Form Google Form style.
    - **Laporan Akhir Tahun**: Membuka Modal Upload Dokumen.
  - Jika laporan Tengah Tahun / Akhir Tahun sudah terisi/terunggah, tampilkan status terunggah dan link preview/download file.

---

## 3. Modal Form: Laporan Tengah Tahun (Google Form Style)
- Modal melayang dengan kartu putih bersih, border aksen atas warna indigo/slate (`border-t-4 border-indigo-600`), header judul & deskripsi singkat.
- Terdiri dari 3 section/field peran:
  1. **Peran 1**: Textarea keterangan (Placeholder: *"Keterangan untuk Peran 1..."*).
  2. **Peran 2**: Textarea keterangan (Placeholder: *"Keterangan untuk Peran 2..."*).
  3. **Peran 3**: Textarea keterangan (Placeholder: *"Keterangan untuk Peran 3..."*).
- Tombol **Kirim Laporan Tengah Tahun** (Ikon SVG minimalis).

---

## 4. Modal Form: Laporan Akhir Tahun (Upload Dokumen Sederhana)
- Modal melayang sederhana.
- Field:
  - File Laporan Berkala Akhir Tahun (PDF, maks 20MB).
  - Catatan Ringkas (Opsional).
- Tombol **Unggah Laporan Akhir Tahun**.

---

## 5. Visual & Ikonografi
- Seluruh icon menggunakan SVG clean stroke (Heroicons v2 style).
- Tanpa emoji AI generic.
