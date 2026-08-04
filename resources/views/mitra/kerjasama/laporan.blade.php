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

<div class="space-y-6" x-data="{ 
    showModalTengah: false, 
    showModalAkhir: false, 
    activeTahun: null 
}">

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
            <p class="text-xs text-gray-500 mt-0.5">Mitra dapat mengisikan laporan penggunaan data untuk Laporan Tengah Tahun dan Laporan Akhir Tahun.</p>
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
    @endif

    <!-- Kartu Laporan Berkala per Jangka Waktu (Tahun) -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-200 bg-slate-50 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Kartu Pelaporan Berdasarkan Jangka Waktu</h3>
                <p class="text-xs text-gray-500 mt-0.5">
                    @if(count($periodeList) > 0)
                        Jangka waktu kerja sama {{ $ks->jangka_waktu_thn }} tahun (Terdapat {{ count($periodeList) }} Kartu Pelaporan Tahun)
                    @else
                        Jumlah kartu tahun dihitung dari Jangka Waktu dan Tanggal Mulai kerja sama
                    @endif
                </p>
            </div>
        </div>

        @if(count($periodeList) === 0)
            <div class="p-8 text-center text-gray-500 text-xs">
                <p class="font-medium text-gray-700 mb-1">Jangka waktu pelaporan belum dapat ditentukan.</p>
                <p>Kartu pelaporan tahunan akan muncul otomatis setelah Admin mengisi Jangka Waktu dan Tanggal Mulai pada tahap finalisasi kerja sama.</p>
            </div>
        @else
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($periodeList as $card)
                <div class="border border-gray-200 rounded-xl bg-white shadow-xs overflow-hidden flex flex-col justify-between">
                    <div>
                        <!-- Header Kartu Tahun -->
                        <div class="bg-slate-100/80 px-5 py-3.5 border-b border-gray-200 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <h4 class="text-sm font-bold text-gray-900">Tahun ke-{{ $card['tahun_ke'] }} ({{ $card['tahun'] }})</h4>
                            </div>
                            <span class="text-[11px] font-semibold text-gray-500 bg-white px-2.5 py-1 rounded-md border border-gray-200">
                                Periode {{ $card['tahun'] }}
                            </span>
                        </div>

                        <div class="p-5 space-y-4">
                            <!-- Section Laporan Tengah Tahun -->
                            @php $tengah = $card['periodes']['Tengah Tahun']; @endphp
                            <div class="p-4 rounded-lg border border-slate-200 bg-slate-50/50 space-y-2">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h5 class="text-xs font-bold text-gray-800">Laporan Tengah Tahun</h5>
                                        <p class="text-[11px] text-gray-500">{{ $tengah['rentang'] }}</p>
                                    </div>
                                    @if($tengah['laporan'])
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 border border-green-300 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Terkirim
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-700 border border-slate-300">
                                            Belum Mengisi
                                        </span>
                                    @endif
                                </div>

                                @if($tengah['laporan'])
                                    <div class="pt-2 text-[11px] text-gray-600 border-t border-slate-200 flex items-center justify-between">
                                        <span>Dikirim {{ $tengah['laporan']->created_at?->format('d M Y') ?? '-' }}</span>
                                        @if($tengah['laporan']->file_path)
                                            <a href="{{ Storage::disk('public')->url($tengah['laporan']->file_path) }}" target="_blank"
                                               class="inline-flex items-center gap-1 text-indigo-600 hover:underline font-semibold">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                Lihat File
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                @if($isReportingActive)
                                <button type="button" 
                                        @click="activeTahun = {{ $card['tahun'] }}; showModalTengah = true"
                                        class="w-full mt-2 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition-colors">
                                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    {{ $tengah['laporan'] ? 'Edit / Isi Ulang Form Tengah Tahun' : 'Isi Form Laporan Tengah Tahun' }}
                                </button>
                                @endif
                            </div>

                            <!-- Section Laporan Akhir Tahun -->
                            @php $akhir = $card['periodes']['Akhir Tahun']; @endphp
                            <div class="p-4 rounded-lg border border-slate-200 bg-slate-50/50 space-y-2">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h5 class="text-xs font-bold text-gray-800">Laporan Akhir Tahun</h5>
                                        <p class="text-[11px] text-gray-500">{{ $akhir['rentang'] }}</p>
                                    </div>
                                    @if($akhir['laporan'])
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 border border-green-300 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Terkirim
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-700 border border-slate-300">
                                            Belum Mengisi
                                        </span>
                                    @endif
                                </div>

                                @if($akhir['laporan'])
                                    <div class="pt-2 text-[11px] text-gray-600 border-t border-slate-200 flex items-center justify-between">
                                        <span class="truncate max-w-[180px]" title="{{ $akhir['laporan']->nama_file }}">{{ $akhir['laporan']->nama_file }}</span>
                                        @if($akhir['laporan']->file_path)
                                            <a href="{{ Storage::disk('public')->url($akhir['laporan']->file_path) }}" target="_blank"
                                               class="inline-flex items-center gap-1 text-indigo-600 hover:underline font-semibold shrink-0">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                Unduh File
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                @if($isReportingActive)
                                <button type="button" 
                                        @click="activeTahun = {{ $card['tahun'] }}; showModalAkhir = true"
                                        class="w-full mt-2 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 transition-colors">
                                    <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    {{ $akhir['laporan'] ? 'Unggah Ulang Dokumen Akhir Tahun' : 'Unggah Dokumen Akhir Tahun' }}
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Riwayat Laporan Berkala Terunggah -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-200 flex items-center justify-between bg-slate-50">
            <h3 class="text-sm font-bold text-gray-900">Riwayat Pelaporan Berkala Terkirim ({{ $ks->reports->count() }})</h3>
        </div>

        @if($ks->reports->count() === 0)
            <div class="p-8 text-center text-gray-500 text-xs">
                <p class="font-medium text-gray-700 mb-1">Belum ada laporan berkala yang dikirim.</p>
                <p>Laporan yang dikirim akan tercatat secara historis di tabel ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-gray-100/80 border-b border-gray-200 text-gray-700 font-bold uppercase">
                            <th class="py-3 px-4">Tahun / Periode</th>
                            <th class="py-3 px-4">Nama File / Keterangan</th>
                            <th class="py-3 px-4">Tanggal Unggah</th>
                            <th class="py-3 px-4">Catatan</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
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
                                @if($rep->file_path)
                                <a href="{{ Storage::disk('public')->url($rep->file_path) }}" target="_blank"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Unduh File
                                </a>
                                @else
                                <span class="text-gray-400 font-mono text-[11px]">Form Terisi</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- MODAL 3A: FORM LAPORAN TENGAH TAHUN (GOOGLE FORM STYLE) -->
    <div x-show="showModalTengah" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(15, 23, 42, 0.6)" @keydown.escape="showModalTengah = false">
        <div class="bg-slate-100 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden border-t-4 border-indigo-600" @click.outside="showModalTengah = false">
            
            <!-- Header Google Form Style -->
            <div class="bg-white p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-200">
                        Form Pelaporan Tengah Tahun
                    </span>
                    <button type="button" @click="showModalTengah = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Laporan Tengah Tahun (<span x-text="activeTahun"></span>)</h3>
                <p class="text-xs text-gray-500 mt-1">Silakan lengkapi keterangan masing-masing peran di bawah ini.</p>
            </div>

            <!-- Form Content -->
            <form action="{{ route('mitra.kerjasama.laporan.store', $ks->kerjasama_id) }}" method="POST" class="flex-1 overflow-y-auto p-6 space-y-4">
                @csrf
                <input type="hidden" name="tahun" :value="activeTahun">
                <input type="hidden" name="periode" value="Tengah Tahun">

                <!-- Card Question 1: Peran 1 -->
                <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-xs space-y-2">
                    <label class="block text-sm font-bold text-gray-800">
                        Peran 1 <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-500">Masukkan keterangan mengenai aktivitas atau capaian Peran 1.</p>
                    <textarea name="peran1" rows="3" placeholder="Keterangan mengenai Peran 1..."
                              class="w-full border border-gray-300 rounded-lg p-3 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50/50"></textarea>
                </div>

                <!-- Card Question 2: Peran 2 -->
                <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-xs space-y-2">
                    <label class="block text-sm font-bold text-gray-800">
                        Peran 2 <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-500">Masukkan keterangan mengenai aktivitas atau capaian Peran 2.</p>
                    <textarea name="peran2" rows="3" placeholder="Keterangan mengenai Peran 2..."
                              class="w-full border border-gray-300 rounded-lg p-3 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50/50"></textarea>
                </div>

                <!-- Card Question 3: Peran 3 -->
                <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-xs space-y-2">
                    <label class="block text-sm font-bold text-gray-800">
                        Peran 3 <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-500">Masukkan keterangan mengenai aktivitas atau capaian Peran 3.</p>
                    <textarea name="peran3" rows="3" placeholder="Keterangan mengenai Peran 3..."
                              class="w-full border border-gray-300 rounded-lg p-3 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50/50"></textarea>
                </div>

                <!-- Catatan Tambahan (Opsional) -->
                <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-xs space-y-2">
                    <label class="block text-xs font-semibold text-gray-700">Catatan / Ringkasan Tambahan (Opsional)</label>
                    <textarea name="catatan" rows="2" placeholder="Catatan tambahan bila ada..."
                              class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-indigo-500 bg-gray-50/50"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showModalTengah = false" 
                            class="px-4 py-2 rounded-lg text-xs font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-5 py-2 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-xs flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Kirim Laporan Tengah Tahun
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 3B: FORM UPLOAD LAPORAN AKHIR TAHUN -->
    <div x-show="showModalAkhir" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(15, 23, 42, 0.6)" @keydown.escape="showModalAkhir = false">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-200" @click.outside="showModalAkhir = false">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-slate-50">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <h3 class="text-sm font-bold text-gray-900">Upload Dokumen Laporan Akhir Tahun (<span x-text="activeTahun"></span>)</h3>
                </div>
                <button type="button" @click="showModalAkhir = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('mitra.kerjasama.laporan.store', $ks->kerjasama_id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="tahun" :value="activeTahun">
                <input type="hidden" name="periode" value="Akhir Tahun">

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">File Dokumen Laporan Akhir Tahun <span class="text-red-500">*</span></label>
                    <input type="file" name="file_laporan" accept=".pdf" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500 bg-white text-gray-700">
                    <p class="text-[11px] text-gray-400 mt-1">Format file wajib PDF (Maksimal 20MB)</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan / Ringkasan Laporan (Opsional)</label>
                    <textarea name="catatan" rows="2" placeholder="Catatan tambahan mengenai laporan akhir tahun..."
                              class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-indigo-500 bg-white"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showModalAkhir = false" 
                            class="px-4 py-2 rounded-lg text-xs font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-5 py-2 rounded-lg text-xs font-semibold text-white bg-slate-800 hover:bg-slate-900 transition-colors shadow-xs flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Unggah Laporan Akhir Tahun
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@section('page-content')
@endsection
