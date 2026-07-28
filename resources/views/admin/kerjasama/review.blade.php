@extends('layouts.admin')

@section('title', 'Review Kerja Sama')
@section('page-title', 'Review: ' . $ks->nama_kl)

@section('page-content')
@php
    $status = (int) $ks->ks_status_dok;
    $hasJadwal = (bool) $ks->tanggal_pembahasan;
    $waitingUndangan = $status === 2 && $hasJadwal && !$ks->hasSuratUndangan();
    $lastReject = collect($ks->review_log)->filter(fn($l) => ($l['label'] ?? '') === 'Ditolak')->last();
    $isRejected = $lastReject && !$ks->ks_status_dok;
@endphp

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
@endif

<div x-data="{
    showSetujui: false,
    showTolak: false,
    showJadwal: false,
    showFinalisasi: false,
    openRiwayat: false,
}" class="space-y-4">

    <!-- Status Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between flex-wrap gap-2">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                <h3 class="text-sm font-semibold text-slate-800">Status Dokumen</h3>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                @switch($status)
                    @case(1)
                        <button @click="showSetujui = true" class="bg-green-500 text-white px-3.5 py-1.5 rounded-lg text-xs font-medium hover:bg-green-600 transition-colors shadow-2xs">Setujui & Jadwalkan</button>
                        <button @click="showTolak = true" class="bg-red-500 text-white px-3.5 py-1.5 rounded-lg text-xs font-medium hover:bg-red-600 transition-colors shadow-2xs">Tolak</button>
                        @break
                    @case(2)
                        @if(!$hasJadwal)
                        <button @click="showJadwal = true" class="bg-amber-500 text-white px-3.5 py-1.5 rounded-lg text-xs font-medium hover:bg-amber-600 transition-colors shadow-2xs">Jadwalkan</button>
                        @endif
                        @break
                    @case(3)
                        <form method="POST" action="{{ route('admin.kerjasama.lanjutPembahasan', $ks->kerjasama_id) }}" onsubmit="return confirm('Lanjutkan ke penandatanganan?')">
                            @csrf
                            <button class="bg-purple-500 text-white px-3.5 py-1.5 rounded-lg text-xs font-medium hover:bg-purple-600 transition-colors shadow-2xs">Lanjut ke Penandatanganan</button>
                        </form>
                        @break
                    @case(4)
                        <button @click="showFinalisasi = true" class="bg-green-500 text-white px-3.5 py-1.5 rounded-lg text-xs font-medium hover:bg-green-600 transition-colors shadow-2xs">Finalisasi</button>
                        @break
                @endswitch
                @if((int)$ks->ks_status_dok >= 5)
                <a href="{{ route('admin.kerjasama.cetak-ringkasan', $ks->kerjasama_id) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition-colors shadow-2xs">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Lampiran Data MoU
                </a>
                @endif
            </div>
        </div>
        <div class="px-5 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                @php
                    $badgeClass = $isRejected ? 'bg-red-50 text-red-800 border border-red-200' : match((int)$ks->ks_status_dok) {
                        1 => 'bg-blue-50 text-blue-800 border border-blue-200',
                        2 => 'bg-yellow-50 text-yellow-800 border border-yellow-200',
                        3 => 'bg-orange-50 text-orange-800 border border-orange-200',
                        4 => 'bg-purple-50 text-purple-800 border border-purple-200',
                        5 => 'bg-emerald-50 text-emerald-800 border border-emerald-200',
                        6 => 'bg-gray-50 text-gray-800 border border-gray-200',
                        default => 'bg-gray-50 text-gray-800 border border-gray-200',
                    };
                @endphp
                <span class="px-3 py-2 text-xs font-semibold rounded-lg shadow-2xs leading-relaxed flex items-center gap-2 {{ $badgeClass }}">
                    <span class="w-2 h-2 rounded-full shrink-0 bg-current"></span>
                    <span>{{ $ks->status_label }}</span>
                </span>
            </div>
            @if($hasJadwal && $status == 2)
            <button @click="showJadwal = true" class="bg-amber-500 text-white px-4 py-1.5 rounded-lg text-xs font-medium hover:bg-amber-600">Ubah Jadwal</button>
            @endif
        </div>
    </div>

    <!-- Rejection Alert -->
    @if($isRejected)
    <div class="bg-red-50 rounded-lg shadow-sm border border-red-200 mb-4 p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <p class="text-sm font-semibold text-red-700">Pengajuan Ditolak</p>
            <p class="text-sm text-red-600 mt-1"><span class="font-medium">Catatan Penolakan:</span> {{ $lastReject['catatan'] ?? 'Pengajuan telah ditolak. Menunggu perbaikan dari mitra.' }}</p>
        </div>
    </div>
    @endif

    <!-- Waiting Undangan -->
    @if($waitingUndangan)
    <div class="bg-green-50 rounded-lg shadow-sm border border-green-200 mb-4">
        <div class="px-5 py-3 flex items-start gap-2">
            <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm text-green-700">Pembahasan telah dijadwalkan. Menunggu mitra mengupload surat undangan pembahasan.</p>
        </div>
    </div>
    @endif

    <!-- Jadwal -->
    @if($hasJadwal)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <h3 class="text-sm font-semibold text-slate-800">Jadwal Pembahasan</h3>
        </div>
        <div class="px-5 py-4">
            <p class="text-sm">{{ $ks->tanggal_pembahasan->format('d M Y, H:i') }}</p>
        </div>
    </div>
    @endif

    <!-- Data Mitra -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <h3 class="text-sm font-semibold text-slate-800">Data Mitra</h3>
        </div>
        <div class="px-5 py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div><span class="text-xs text-gray-400">Instansi</span><p class="text-sm">{{ $ks->nama_kl }}</p></div>
            <div><span class="text-xs text-gray-400">Jenis</span><p class="text-sm">{{ $ks->jenis->nama_jenis ?? '-' }}</p></div>
            <div><span class="text-xs text-gray-400">Tingkat</span><p class="text-sm">{{ $ks->tingkat->nama_tingkat ?? '-' }}</p></div>
        </div>
    </div>

    <!-- Data Final (setelah TTD) -->
    @if($status >= 5)
    <div class="bg-white rounded-lg shadow-sm border border-green-200 mb-4">
        <div class="px-5 py-3 border-b border-green-100 flex items-center justify-between bg-green-50">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="text-sm font-semibold text-green-800">Data Final Kerja Sama</h3>
            </div>
            <button @click="showFinalisasi = true" class="bg-green-500 text-white px-3 py-1 rounded text-xs font-medium hover:bg-green-600">Edit Finalisasi</button>
        </div>
        <div class="px-5 py-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            @if($ks->pihak1)<div><span class="text-xs text-gray-400">Pihak 1</span><p>{{ $ks->pihak1 }}</p></div>@endif
            @if($ks->pihak2)<div><span class="text-xs text-gray-400">Pihak 2</span><p>{{ $ks->pihak2 }}</p></div>@endif
            @if($ks->tentang)<div class="md:col-span-2"><span class="text-xs text-gray-400">Perihal</span><p>{{ $ks->tentang }}</p></div>@endif
            @if($ks->jangka_waktu_thn)<div><span class="text-xs text-gray-400">Jangka Waktu</span><p>{{ $ks->jangka_waktu_thn }} tahun</p></div>@endif
            @if($ks->tanggal_mulai_ks)<div><span class="text-xs text-gray-400">Tgl Mulai</span><p>{{ $ks->tanggal_mulai_ks->format('d M Y') }}</p></div>@endif
            @if($ks->tanggal_selesai_ks)<div><span class="text-xs text-gray-400">Tgl Berakhir</span><p>{{ $ks->tanggal_selesai_ks->format('d M Y') }}</p></div>@endif
            @if($ks->nomor_pihak1)<div><span class="text-xs text-gray-400">No. Pihak 1</span><p>{{ $ks->nomor_pihak1 }}</p></div>@endif
            @if($ks->nomor_pihak2)<div><span class="text-xs text-gray-400">No. Pihak 2</span><p>{{ $ks->nomor_pihak2 }}</p></div>@endif
            @if($ks->ttd_pihak1)<div><span class="text-xs text-gray-400">TTD Pihak 1</span><p>{{ $ks->ttd_pihak1 }}</p></div>@endif
            @if($ks->ttd_pihak2)<div><span class="text-xs text-gray-400">TTD Pihak 2</span><p>{{ $ks->ttd_pihak2 }}</p></div>@endif
            @if($ks->kode_wilayah)<div><span class="text-xs text-gray-400">Kode Wilayah</span><p>{{ $ks->kode_wilayah }}</p></div>@endif
            @if($ks->jumlah_kl_terlibat > 1)<div><span class="text-xs text-gray-400">Jumlah K/L</span><p>{{ $ks->jumlah_kl_terlibat }}</p></div>@endif
            @if($ks->metode)<div><span class="text-xs text-gray-400">Metode</span><p>{{ $ks->metode->nama_metode }}</p></div>@endif
            @if($ks->implementasi)<div><span class="text-xs text-gray-400">Implementasi</span><p>{{ $ks->implementasi->nama_status }}</p></div>@endif
        </div>
    </div>
    @endif

    <!-- Widget Diskusi & Chat Interaktif Mitra ↔ Admin -->
    <x-chat-widget :kerjasamaId="$ks->kerjasama_id" currentRole="admin" senderName="Admin Pusdatin" />

    <!-- Hasil Pemilihan Data oleh Mitra (Terstruktur per Database & Tabel) -->
    @if($status >= 5)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4">
        <div class="px-5 py-3 flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-sm font-semibold text-slate-800">Hasil Pemilihan Data oleh Mitra</h3>
            </div>
            <div class="flex items-center gap-3">
                @if($ks->status_pemilihan_data === 'submitted')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                        <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Telah Diajukan ({{ $ks->pemilihanData->count() }} Kolom)
                    </span>
                    <a href="{{ route('admin.kerjasama.persetujuan-data.form', $ks->kerjasama_id) }}" 
                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-2xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Kelola Persetujuan Data per Item
                    </a>
                @elseif($ks->pemilihanData->isNotEmpty())
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-300">
                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        Draf Terisi (Belum Diajukan Mitra)
                    </span>
                    <button type="button" disabled 
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold text-gray-400 bg-gray-100 border border-gray-200 cursor-not-allowed"
                            title="Kelola persetujuan data hanya aktif setelah Mitra mengajukan pemilihan data">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Kelola Persetujuan (Menunggu Pengajuan Mitra)
                    </button>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-300">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        Mitra Belum Memilih Data
                    </span>
                @endif
            </div>
        </div>

    </div>
    @endif
    <!-- Riwayat Pelaporan Berkala Mitra -->
    @if($status >= 5 || $ks->reports->isNotEmpty())
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-sm font-semibold text-slate-800">Riwayat Pelaporan Berkala Mitra</h3>
            </div>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                {{ $ks->reports->count() }} Laporan
            </span>
        </div>
        <div class="p-5">
            @if($ks->reports->isEmpty())
                <p class="text-sm text-gray-500 italic">Belum ada laporan berkala yang diunggah oleh mitra.</p>
            @else
                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold uppercase">
                                <th class="py-2.5 px-3">Tahun</th>
                                <th class="py-2.5 px-3">Periode</th>
                                <th class="py-2.5 px-3">File Laporan</th>
                                <th class="py-2.5 px-3">Catatan</th>
                                <th class="py-2.5 px-3">Tanggal Upload</th>
                                <th class="py-2.5 px-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($ks->reports as $report)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-2.5 px-3 font-semibold text-slate-800">{{ $report->tahun }}</td>
                                <td class="py-2.5 px-3 font-medium text-indigo-700">
                                    <span class="px-2 py-0.5 rounded bg-indigo-50 border border-indigo-100 text-[11px]">
                                        {{ $report->periode }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 font-mono text-gray-700">{{ $report->nama_file ?? 'File Laporan' }}</td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $report->catatan ?? '-' }}</td>
                                <td class="py-2.5 px-3 text-gray-500">{{ $report->created_at ? $report->created_at->format('d M Y, H:i') : '-' }}</td>
                                <td class="py-2.5 px-3 text-center">
                                    @php
                                        $fileUrl = Storage::disk('public')->exists($report->file_path)
                                            ? Storage::disk('public')->url($report->file_path)
                                            : asset('storage/' . $report->file_path);
                                    @endphp
                                    <a href="{{ $fileUrl }}" target="_blank"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        Download / Lihat File
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
    @endif

    <!-- Kontak -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            <h3 class="text-sm font-semibold text-slate-800">Kontak</h3>
        </div>
        <div class="px-5 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><span class="text-xs text-gray-400">Narahubung Adm</span><p class="text-sm">{{ $ks->narahubung_adm ?? '-' }}</p></div>
            <div><span class="text-xs text-gray-400">No. Kontak Adm</span><p class="text-sm">{{ $ks->nomor_cp_adm ?? '-' }}</p></div>
            <div><span class="text-xs text-gray-400">Narahubung Teknis</span><p class="text-sm">{{ $ks->narahubung_teknis ?? '-' }}</p></div>
            <div><span class="text-xs text-gray-400">No. Kontak Teknis</span><p class="text-sm">{{ $ks->nomor_cp_teknis ?? '-' }}</p></div>
        </div>
    </div>

    <!-- Dokumen -->
    @php
        $folderFiles = $ks->folder_ks_display;
        $hasDokumen = $folderFiles || $ks->dokumen_ks;
    @endphp
    @if($hasDokumen)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4" x-data="{ open: false, src: '' }">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
            <h3 class="text-sm font-semibold text-slate-800">Dokumen</h3>
        </div>
        <div class="px-5 py-4">
            <ul class="divide-y divide-gray-100 text-sm">
                @foreach($folderFiles as $doc)
                <li class="flex items-center gap-2 py-2">
                    <span>{{ $doc['label'] }}</span>
                    <span class="text-gray-300">—</span>
                    <button type="button" @click="open = true; src = '{{ $doc['url'] }}'" class="text-indigo-500 text-xs hover:underline">Lihat</button>
                </li>
                @endforeach
                @if($ks->dokumen_ks)
                <li class="flex items-center gap-2 py-2">
                    <span>Dokumen Final (TTD)</span>
                    <span class="text-gray-300">—</span>
                    <button type="button" @click="open = true; src = '{{ Storage::disk('public')->exists($ks->dokumen_ks) ? Storage::disk('public')->url($ks->dokumen_ks) : $ks->dokumen_ks }}'" class="text-indigo-500 text-xs hover:underline">Lihat</button>
                </li>
                @endif
                @if($ks->dokumen_pendukung)
                <li class="flex items-center gap-2 py-2">
                    <span>Dokumen Pendukung</span>
                    <span class="text-gray-300">—</span>
                    <span class="text-sm">{{ $ks->dokumen_pendukung }}</span>
                </li>
                @endif
            </ul>
        </div>
        <!-- Preview Modal -->
        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.6)">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl h-[85vh] flex flex-col" @click.outside="open = false">
                <div class="flex justify-between items-center px-6 py-3 border-b">
                    <span class="font-semibold text-gray-700">Pratinjau Dokumen</span>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                </div>
                <iframe :src="src" class="flex-1 w-full rounded-b-xl" frameborder="0"></iframe>
            </div>
        </div>
    </div>
    @endif



    <!-- Riwayat -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between cursor-pointer" @click="openRiwayat = !openRiwayat">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="text-sm font-semibold text-slate-800">Riwayat Aktivitas</h3>
            </div>
            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="openRiwayat ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
        <div class="px-5 py-4" x-show="openRiwayat">
            @include('components.review-log-timeline', ['logs' => $ks->reviewLogs])
        </div>
    </div>

    <!-- MODAL: Setujui & Jadwalkan -->
    <div x-show="showSetujui" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-transition>
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6" @click.outside="showSetujui = false">
            <h3 class="text-lg font-semibold text-green-600 mb-4">Setujui & Jadwalkan Pembahasan</h3>
            <form method="POST" action="{{ route('admin.kerjasama.setujui', $ks->kerjasama_id) }}" x-data="{ tgl: '{{ date('Y-m-d') }}', jam: '09:00' }" @submit="$refs.fullDate1.value = tgl + ' ' + jam">
                @csrf
                <input type="hidden" name="tanggal_pembahasan" x-ref="fullDate1" required>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembahasan <span class="text-red-500">*</span></label>
                        <input type="date" x-model="tgl" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Pembahasan (24 Jam) <span class="text-red-500">*</span></label>
                        <input type="text" x-model="jam" placeholder="21:45 atau ketik 2145" maxlength="5" required
                               oninput="formatJamInput(this)" onblur="validateJamOnBlur(this)"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white font-mono text-base tracking-wider">
                         
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-5">
                    <button type="button" @click="showSetujui = false" class="border border-gray-300 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Batal</button>
                    <button type="submit" class="bg-green-500 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-600">Setujui & Jadwalkan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Tolak -->
    <div x-show="showTolak" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-transition>
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6" @click.outside="showTolak = false">
            <h3 class="text-lg font-semibold text-red-600 mb-4">Tolak Pengajuan</h3>
            <form method="POST" action="{{ route('admin.kerjasama.tolak', $ks->kerjasama_id) }}">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Penolakan <span class="text-red-500">*</span></label>
                    <textarea name="catatan_penolakan" rows="4" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Tulis catatan penolakan..."></textarea>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" @click="showTolak = false" class="border border-gray-300 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Batal</button>
                    <button type="submit" class="bg-red-500 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-red-600">Tolak</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Jadwalkan -->
    <div x-show="showJadwal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-transition>
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6" @click.outside="showJadwal = false">
            <h3 class="text-lg font-semibold text-amber-600 mb-4">{{ $hasJadwal ? 'Ubah Jadwal' : 'Jadwalkan Pembahasan' }}</h3>
            <form method="POST" action="{{ route('admin.kerjasama.jadwalkan', $ks->kerjasama_id) }}" x-data="{ tgl: '{{ $hasJadwal ? $ks->tanggal_pembahasan->format('Y-m-d') : date('Y-m-d') }}', jam: '{{ $hasJadwal ? $ks->tanggal_pembahasan->format('H:i') : '09:00' }}' }" @submit="$refs.fullDate2.value = tgl + ' ' + jam">
                @csrf
                <input type="hidden" name="tanggal_pembahasan" x-ref="fullDate2" required>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembahasan <span class="text-red-500">*</span></label>
                        <input type="date" x-model="tgl" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Pembahasan (24 Jam) <span class="text-red-500">*</span></label>
                        <input type="text" x-model="jam" placeholder="21:45 atau ketik 2145" maxlength="5" required
                               oninput="formatJamInput(this)" onblur="validateJamOnBlur(this)"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white font-mono text-base tracking-wider">
                         
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-5">
                    <button type="button" @click="showJadwal = false" class="border border-gray-300 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Batal</button>
                    <button type="submit" class="bg-amber-500 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-amber-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Finalisasi -->
    <div x-show="showFinalisasi" x-cloak class="fixed inset-0 z-50 flex items-start justify-center bg-black/50 overflow-y-auto py-10" x-transition>
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl p-6" @click.outside="showFinalisasi = false">
            <h3 class="text-lg font-semibold text-green-600 mb-4">{{ $status >= 5 ? 'Edit Finalisasi' : 'Finalisasi Kerja Sama' }}</h3>
            <form method="POST" action="{{ $status >= 5 ? route('admin.kerjasama.updateFinalisasi', $ks->kerjasama_id) : route('admin.kerjasama.finalisasi', $ks->kerjasama_id) }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-3 max-h-[60vh] overflow-y-auto pr-1">
                    @if($status < 5)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Dokumen Final (TTD)</label>
                        <input type="file" name="dokumen_final" accept=".pdf,.docx,.zip" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    @endif
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Penandatangan Pihak 1</label>
                            <input type="text" name="ttd_pihak1" value="{{ $ks->ttd_pihak1 }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Penandatangan Pihak 2</label>
                            <input type="text" name="ttd_pihak2" value="{{ $ks->ttd_pihak2 }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Kode Wilayah</label>
                            <input type="text" name="kode_wilayah" maxlength="20" value="{{ $ks->kode_wilayah }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jumlah K/L</label>
                            <input type="number" name="jumlah_kl_terlibat" min="1" value="{{ $ks->jumlah_kl_terlibat }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Pihak 1</label>
                            <input type="text" name="pihak1" value="{{ $ks->pihak1 }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Pihak 2</label>
                            <input type="text" name="pihak2" value="{{ $ks->pihak2 }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Perihal</label>
                        <textarea name="tentang" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ $ks->tentang }}</textarea>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jangka (thn)</label>
                            <input type="number" name="jangka_waktu_thn" min="1" value="{{ $ks->jangka_waktu_thn }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tgl Mulai</label>
                            <input type="date" name="tanggal_mulai_ks" value="{{ $ks->tanggal_mulai_ks?->format('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tgl Berakhir</label>
                            <input type="date" name="tanggal_selesai_ks" value="{{ $ks->tanggal_selesai_ks?->format('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No Pihak 1</label>
                            <input type="text" name="nomor_pihak1" value="{{ $ks->nomor_pihak1 }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No Pihak 2</label>
                            <input type="text" name="nomor_pihak2" value="{{ $ks->nomor_pihak2 }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Metode</label>
                            <select name="ks_metode" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                <option value="">-- Pilih --</option>
                                @foreach($metodeList as $id => $name)
                                <option value="{{ $id }}" {{ $ks->ks_metode == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Implementasi</label>
                            <select name="ks_implementasi" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                <option value="">-- Pilih --</option>
                                @foreach($implementasiList as $id => $name)
                                <option value="{{ $id }}" {{ $ks->ks_implementasi == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-gray-100">
                    <button type="button" @click="showFinalisasi = false" class="border border-gray-300 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Batal</button>
                    <button type="submit" class="bg-green-500 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-600">{{ $status >= 5 ? 'Simpan' : 'Finalisasi' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
