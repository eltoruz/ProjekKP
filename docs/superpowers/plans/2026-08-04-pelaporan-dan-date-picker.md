# Date Picker & Pelaporan Berkala (Tengah Tahun & Akhir Tahun) Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Menambahkan pilihan tahun di komponen date-picker dan menyusun ulang Halaman Pelaporan Berkala Mitra berbasis Kartu Jangka Waktu Tahun dengan dua tombol Laporan Tengah Tahun (form Google Form UI 3 peran) & Laporan Akhir Tahun (form upload dokumen sederhana) tanpa emoji generic AI.

**Architecture:** 
1. Di `date-picker.blade.php` & `ksDatePicker` (di `app.blade.php`), tambahkan dropdown tahun `<select>` untuk manipulasi cepat `viewY`.
2. Di `LaporanController.php`, ganti istilah "Semester" menjadi "Tengah Tahun" dan "Akhir Tahun", dan susun data `periodeList` terkelompok per Kartu Tahun.
3. Di `laporan.blade.php`, susun kartu visual berbasis jangka waktu (Tahun ke-1 s/d Tahun ke-N). Di setiap kartu tahun tampilkan dua tombol aksi (Tengah Tahun & Akhir Tahun) serta dua modal interaktif (Google Form style untuk Tengah Tahun & Simple PDF Upload untuk Akhir Tahun).

**Tech Stack:** Laravel (Blade), Alpine.js, Tailwind CSS, SVG Minimalist Icons (Heroicons style).

---

### Task 1: Pilihan Tahun pada Date Picker Component

**Files:**
- Modify: `resources/views/layouts/app.blade.php:72-150`
- Modify: `resources/views/components/date-picker.blade.php:40-50`

- [ ] **Step 1: Update function `ksDatePicker` di `app.blade.php` untuk mendukung penanganan perubahan tahun**

Tambahkan method `yearOptions` atau penanganan ubah tahun `setYear(y)` pada objek `ksDatePicker`:

```js
setYear(year) {
    this.viewY = parseInt(year);
},
get yearRange() {
    const current = this.viewY || new Date().getFullYear();
    const start = current - 5;
    const end = current + 10;
    const years = [];
    for (let y = start; y <= end; y++) {
        years.push(y);
    }
    return years;
}
```

- [ ] **Step 2: Update template Blade `components/date-picker.blade.php` dengan Dropdown Tahun**

Ganti tampilan header navigasi bulan/tahun agar menyertakan `<select>` pilihan tahun yang terhubung ke `viewY`:

```html
<div class="flex items-center justify-between mb-3 gap-1">
    <button type="button" @click="shiftMonth(-1)" aria-label="Bulan sebelumnya"
            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    
    <div class="flex items-center gap-1.5">
        <span class="text-xs font-bold text-gray-900" x-text="window.KS_BULAN[viewM]"></span>
        <select :value="viewY" @change="setYear($event.target.value)"
                class="text-xs font-bold text-gray-900 bg-gray-50 border border-gray-200 rounded-md px-1.5 py-0.5 focus:outline-none focus:ring-1 focus:ring-indigo-500 cursor-pointer">
            <template x-for="y in yearRange" :key="y">
                <option :value="y" x-text="y" :selected="y === viewY"></option>
            </template>
        </select>
    </div>

    <button type="button" @click="shiftMonth(1)" aria-label="Bulan berikutnya"
            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>
</div>
```

---

### Task 2: Update Controller Backend Pelaporan (`LaporanController.php`)

**Files:**
- Modify: `app/Http/Controllers/Mitra/LaporanController.php`

- [ ] **Step 1: Ubah penamaan periode dan pengelompokan tahun pada `susunPeriodeLaporan`**

Ganti `Semester 1` & `Semester 2` menjadi `Tengah Tahun` & `Akhir Tahun`, serta return data yang terkelompok per Kartu Tahun:

```php
private function susunPeriodeLaporan(Kerjasama $ks): array
{
    $jangkaWaktu = (int) $ks->jangka_waktu_thn;

    if ($jangkaWaktu < 1 || !$ks->tanggal_mulai_ks) {
        return [];
    }

    $laporanTerunggah = $ks->reports->keyBy(fn ($rep) => $rep->tahun . '|' . $rep->periode);
    $tahunMulai = (int) $ks->tanggal_mulai_ks->year;

    $periodeDefinitions = [
        'Tengah Tahun' => ['rentang' => 'Januari - Juni'],
        'Akhir Tahun' => ['rentang' => 'Juli - Desember'],
    ];

    $tahunCards = [];

    for ($i = 0; $i < $jangkaWaktu; $i++) {
        $tahun = $tahunMulai + $i;
        $tahunIndex = $i + 1;

        $periodes = [];
        foreach ($periodeDefinitions as $periodeName => $info) {
            $laporan = $laporanTerunggah->get($tahun . '|' . $periodeName);
            $periodes[$periodeName] = [
                'nama' => $periodeName,
                'rentang' => $info['rentang'],
                'laporan' => $laporan,
                'status' => $laporan ? 'terkirim' : 'belum',
            ];
        }

        $tahunCards[] = [
            'tahun_ke' => $tahunIndex,
            'tahun' => $tahun,
            'periodes' => $periodes,
        ];
    }

    return $tahunCards;
}
```

- [ ] **Step 2: Update validasi `store` untuk mengizinkan `Tengah Tahun` dan `Akhir Tahun`**

In `store` method, update validation rule for `periode`:
```php
'periode' => 'required|string|in:Tengah Tahun,Akhir Tahun',
```

---

### Task 3: Redesain Tampilan Halaman Laporan Mitra (`laporan.blade.php`)

**Files:**
- Modify: `resources/views/mitra/kerjasama/laporan.blade.php`

- [ ] **Step 1: Hilangkan kata Semester & Ganti Card Layout dengan Kartu Tahun (Jangka Waktu)**

Tampilkan kartu per tahun (`$tahunCards`). Di tiap kartu tahun tampilkan 2 tombol aksi:
- Tombol 1: **Laporan Tengah Tahun** (membuka modal form Google Form UI)
- Tombol 2: **Laporan Akhir Tahun** (membuka modal form Upload PDF)
Menggunakan ikon SVG minimalis.

- [ ] **Step 2: Buat Modal Google Form Style untuk "Laporan Tengah Tahun"**

Modal Alpine.js bergaya Google Form:
- Header dengan aksen top border `border-t-4 border-indigo-600`, judul "Form Laporan Tengah Tahun".
- 3 Field Role (UI visual):
  - **Peran 1**: Label & `<textarea>` keterangan (placeholder: "Keterangan terkait Peran 1...")
  - **Peran 2**: Label & `<textarea>` keterangan (placeholder: "Keterangan terkait Peran 2...")
  - **Peran 3**: Label & `<textarea>` keterangan (placeholder: "Keterangan terkait Peran 3...")
- Tombol Kirim & Simpan.

- [ ] **Step 3: Buat Modal Simple Upload Dokumen untuk "Laporan Akhir Tahun"**

Modal Alpine.js sederhana:
- Input File `.pdf` (Maks 20MB).
- Catatan opsional.
- Tombol Unggah.

---

### Task 4: Verifikasi & Test Runtime

- [ ] **Step 1: Test ketersediaan rute dan pengujian syntax PHP/Blade**
- [ ] **Step 2: Uji fungsionalitas Date Picker & Halaman Laporan di browser**
