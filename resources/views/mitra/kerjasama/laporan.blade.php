@extends('layouts.mitra')

@section('title', 'Pelaporan Berkala Data Mitra')
@section('page-title', 'Pelaporan Berkala Data Mitra')

@section('page-content')
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm font-medium flex items-center justify-between">
    <div class="flex items-center gap-2">
        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif

@if(session('error'))
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm font-medium flex items-center justify-between">
    <div class="flex items-center gap-2">
        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ session('error') }}</span>
    </div>
</div>
@endif

<div class="space-y-6">

    <!-- Header & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('mitra.kerjasama.index') }}" class="hover:text-indigo-600">Kerja Sama</a>
                <span>/</span>
                <a href="{{ route('mitra.kerjasama.show', $ks->kerjasama_id) }}" class="hover:text-indigo-600">{{ Str::limit($ks->nama_kl, 30) }}</a>
                <span>/</span>
                <span class="text-gray-800 font-medium">Pelaporan Berkala</span>
            </div>
            <div class="flex items-center gap-3">
                <h2 class="text-xl font-bold text-gray-900">Pelaporan Berkala Penggunaan Data</h2>
                @if($isReportingActive)
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-300 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Pelaporan Aktif
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300 inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Belum Aktif
                    </span>
                @endif
            </div>
            <p class="text-xs text-gray-500 mt-0.5">Mitra wajib mengunggah laporan penggunaan data 2 kali per tahun (Semester 1 & Semester 2)</p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('mitra.kerjasama.show', $ks->kerjasama_id) }}" 
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-gray-300 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-colors shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Detail
            </a>
        </div>
    </div>

    <!-- Status Syarat Pelaporan Berkala -->
    @if(!$isReportingActive)
    <div class="bg-amber-50 border border-amber-300 rounded-xl p-5 text-amber-900 shadow-xs space-y-3">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div>
                <strong class="text-sm font-bold block mb-1">Fitur Upload Laporan Berkala Belum Aktif</strong>
                <p class="text-xs text-amber-800">
                    Menu unggah laporan akan terbuka secara otomatis jika kedua syarat berikut telah terpenuhi oleh Admin Pusdatin:
                </p>
                <ul class="mt-2 space-y-1 text-xs font-medium">
                    <li class="flex items-center gap-2">
                        @if($approvedItems->count() > 0)
                            <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Minimal 1 item data disetujui Admin (Terpenuhi: {{ $approvedItems->count() }} item approved)
                        @else
                            <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Minimal 1 item data disetujui Admin (Belum terpenuhi)
                        @endif
                    </li>
                    <li class="flex items-center gap-2">
                        @if((int)$ks->ks_implementasi === 3)
                            <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Status Implementasi Layanan / Pertukaran Data diset 'Aktif' (Terpenuhi)
                        @else
                            <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Status Implementasi Layanan diset 'Aktif' oleh Admin (Status saat ini: <strong>{{ $ks->implementasi?->nama_status ?? 'Belum Aktif' }}</strong>)
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @else

    <!-- Form Upload Laporan Berkala -->
    <div class="bg-white rounded-xl border border-indigo-200 p-6 shadow-sm">
        <h3 class="text-base font-bold text-gray-900 mb-1 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            Upload Laporan Berkala Baru
        </h3>
        <p class="text-xs text-gray-500 mb-4">Silakan pilih tahun, periode semester, dan unggah file laporan penggunaan data (PDF max 20MB).</p>

        <form action="{{ route('mitra.kerjasama.laporan.store', $ks->kerjasama_id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tahun Laporan <span class="text-red-500">*</span></label>
                    <select name="tahun" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500 bg-white">
                        @php $currYear = (int)date('Y'); @endphp
                        @for($y = $currYear; $y >= $currYear - 3; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Periode Laporan <span class="text-red-500">*</span></label>
                    <select name="periode" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500 bg-white">
                        <option value="Semester 1">Semester 1 (Januari - Juni)</option>
                        <option value="Semester 2">Semester 2 (Juli - Desember)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">File Laporan Berkala <span class="text-red-500">*</span></label>
                <input type="file" name="file_laporan" accept=".pdf" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500 bg-white text-gray-700">
                <p class="text-[11px] text-gray-400 mt-1">Format yang diizinkan: .pdf (Maksimal 20MB)</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan / Ringkasan Laporan (Opsional)</label>
                <textarea name="catatan" rows="2" placeholder="Tambahkan catatan ringkas jika ada..."
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500 bg-white"></textarea>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-2.5 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Unggah Laporan Berkala
                </button>
            </div>
        </form>
    </div>
    @endif

    <!-- Kartu Laporan Berkala per Periode -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-200 bg-slate-50 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Laporan Berkala per Periode</h3>
                <p class="text-xs text-gray-500 mt-0.5">
                    @if(count($periodeList) > 0)
                        Jangka waktu {{ $ks->jangka_waktu_thn }} tahun &times; 2 semester = {{ count($periodeList) }} periode pelaporan
                    @else
                        Jumlah periode dihitung dari Jangka Waktu dan Tanggal Mulai kerja sama
                    @endif
                </p>
            </div>
            @if(count($periodeList) > 0)
            @php $sudahTerkirim = collect($periodeList)->where('status', 'terkirim')->count(); @endphp
            <span class="px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                {{ $sudahTerkirim }} / {{ count($periodeList) }} Terkirim
            </span>
            @endif
        </div>

        @if(count($periodeList) === 0)
            <div class="p-8 text-center text-gray-500 text-xs">
                <p class="font-medium text-gray-700 mb-1">Periode pelaporan belum dapat ditentukan.</p>
                <p>Kartu periode akan muncul otomatis setelah Admin mengisi Jangka Waktu dan Tanggal Mulai pada tahap finalisasi kerja sama.</p>
            </div>
        @else
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($periodeList as $item)
                @php
                    $kartuClass = match($item['status']) {
                        'terkirim' => 'border-green-200 bg-green-50/50',
                        'terlewat' => 'border-red-200 bg-red-50/50',
                        'berjalan' => 'border-amber-200 bg-amber-50/50',
                        default => 'border-gray-200 bg-white',
                    };
                    $badge = match($item['status']) {
                        'terkirim' => ['Terkirim', 'bg-green-100 text-green-800 border-green-300'],
                        'terlewat' => ['Terlewat', 'bg-red-100 text-red-800 border-red-300'],
                        'berjalan' => ['Berjalan', 'bg-amber-100 text-amber-800 border-amber-300'],
                        default => ['Mendatang', 'bg-gray-100 text-gray-600 border-gray-300'],
                    };
                @endphp
                <div class="border rounded-xl p-4 {{ $kartuClass }} transition-colors">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $item['periode'] }}</p>
                            <p class="text-xs text-gray-500">{{ $item['tahun'] }} &middot; {{ $item['rentang'] }}</p>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border shrink-0 {{ $badge[1] }}">{{ $badge[0] }}</span>
                    </div>

                    @if($item['laporan'])
                        <div class="mt-3 pt-3 border-t border-gray-200/70 space-y-1.5">
                            <p class="text-[11px] text-gray-600 truncate" title="{{ $item['laporan']->nama_file }}">{{ $item['laporan']->nama_file }}</p>
                            <p class="text-[11px] text-gray-400">Diunggah {{ $item['laporan']->created_at?->format('d M Y, H:i') ?? '-' }}</p>
                            <a href="{{ Storage::disk('public')->url($item['laporan']->file_path) }}" target="_blank"
                               class="inline-flex items-center gap-1 mt-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold text-indigo-600 bg-white hover:bg-indigo-50 border border-indigo-200 transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Unduh File
                            </a>
                        </div>
                    @else
                        <div class="mt-3 pt-3 border-t border-gray-200/70">
                            <p class="text-[11px] text-gray-500">Belum ada laporan diunggah</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">Batas periode: {{ $item['batas_akhir']->format('d M Y') }}</p>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Riwayat Laporan Berkala yang Diunggah -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-200 flex items-center justify-between bg-slate-50">
            <h3 class="text-sm font-bold text-gray-900">Riwayat Laporan Berkala Terunggah ({{ $ks->reports->count() }})</h3>
        </div>

        @if($ks->reports->count() === 0)
            <div class="p-8 text-center text-gray-500 text-xs">
                <p class="font-medium text-gray-700 mb-1">Belum ada laporan berkala yang diunggah.</p>
                <p>Laporan yang diunggah akan tercatat secara historis di tabel ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-gray-100/80 border-b border-gray-200 text-gray-700 font-bold uppercase">
                            <th class="py-3 px-4">Tahun / Periode</th>
                            <th class="py-3 px-4">Nama File</th>
                            <th class="py-3 px-4">Tanggal Unggah</th>
                            <th class="py-3 px-4">Catatan</th>
                            <th class="py-3 px-4 text-center">Aksi / File</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($ks->reports as $rep)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4 font-bold text-gray-900">
                                <span class="px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200 text-xs">
                                    {{ $rep->periode }} {{ $rep->tahun }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-medium text-gray-800">{{ $rep->nama_file }}</td>
                            <td class="py-3 px-4 text-gray-500">{{ $rep->created_at?->format('d M Y, H:i') ?? '-' }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $rep->catatan ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ Storage::disk('public')->url($rep->file_path) }}" target="_blank"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Unduh File
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
