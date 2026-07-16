@extends('layouts.mitra')

@section('title', 'Detail Kerja Sama')
@section('page-title', 'Detail Kerja Sama')

@section('page-content')
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('error') }}</div>
@endif

<div class="space-y-6">
    <!-- Status Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500">Status:</span>
                <x-status-badge :status="$kerjasama->status_pengajuan" :label="$kerjasama->status_label" />
                @if($kerjasama->statusDok)
                    <span class="text-xs text-gray-400">({{ $kerjasama->statusDok->nama_status }})</span>
                @endif
            </div>
            @if($kerjasama->ks_jenis == 3)
            <div class="flex gap-2">
                @if($canEdit && $kerjasama->status_pengajuan !== 'DITOLAK')
                    <a href="{{ route('mitra.kerjasama.edit', $kerjasama->kerjasama_id) }}" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Edit</a>
                @endif
                @if($kerjasama->status_pengajuan === 'UPLOAD_DOKUMEN')
                    <form action="{{ route('mitra.kerjasama.ajukan', $kerjasama->kerjasama_id) }}" method="POST" onsubmit="return confirm('Setelah diajukan, data tidak dapat diubah. Lanjutkan?')">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">Ajukan ke Admin</button>
                    </form>
                @endif
                @if($kerjasama->status_pengajuan === 'DITOLAK')
                    <a href="{{ route('mitra.kerjasama.upload-ulang', $kerjasama->kerjasama_id) }}" class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-700">Upload Ulang</a>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Workflow Timeline (hanya untuk NK) -->
    @if($kerjasama->ks_jenis == 3)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Progress Workflow</h3>
        <x-workflow-stepper :current="$kerjasama->status_pengajuan" />
    </div>
    @endif

    <!-- Jadwal Pembahasan -->
    @if($kerjasama->tanggal_pembahasan_ks)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-base font-semibold text-navy mb-3">Jadwal Pembahasan</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
            <div><span class="text-gray-500">Tanggal:</span> <span class="font-medium">{{ \Carbon\Carbon::parse($kerjasama->tanggal_pembahasan_ks)->format('d M Y') }}</span></div>
            <div><span class="text-gray-500">Jam:</span> <span class="font-medium">{{ substr($kerjasama->jam_pembahasan, 0, 5) }}</span></div>
            <div><span class="text-gray-500">Lokasi:</span> <span class="font-medium">{{ $kerjasama->lokasi_pembahasan ?? '-' }}</span></div>
            @if($kerjasama->link_meeting)
            <div class="col-span-2"><span class="text-gray-500">Link Meeting:</span> <a href="{{ $kerjasama->link_meeting }}" target="_blank" class="text-primary font-medium hover:underline">{{ $kerjasama->link_meeting }}</a></div>
            @endif
            @if($kerjasama->pic_pembahasan)
            <div><span class="text-gray-500">PIC:</span> <span class="font-medium">{{ $kerjasama->pic_pembahasan }}</span></div>
            @endif
            @if($kerjasama->nomor_pihak1 || $kerjasama->nomor_pihak2)
            <div><span class="text-gray-500">Nomor Pihak 1:</span> <span class="font-medium">{{ $kerjasama->nomor_pihak1 ?? '-' }}</span></div>
            <div><span class="text-gray-500">Nomor Pihak 2:</span> <span class="font-medium">{{ $kerjasama->nomor_pihak2 ?? '-' }}</span></div>
            @endif
        </div>
    </div>
    @endif

    <!-- Data Final (setelah TTD) -->
    @if($kerjasama->status_pengajuan === 'SELESAI')
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-base font-semibold text-navy mb-3">Data Final Kerja Sama</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
            <div><span class="text-gray-500">Jangka Waktu:</span> <span class="font-medium">{{ $kerjasama->jangka_waktu_thn ? $kerjasama->jangka_waktu_thn.' tahun' : '-' }}</span></div>
            <div><span class="text-gray-500">Tanggal Mulai:</span> <span class="font-medium">{{ $kerjasama->tanggal_mulai_ks?->format('d M Y') ?? '-' }}</span></div>
            <div><span class="text-gray-500">Tanggal Berakhir:</span> <span class="font-medium">{{ $kerjasama->tanggal_selesai_ks?->format('d M Y') ?? '-' }}</span></div>
            @if($kerjasama->metode)
            <div><span class="text-gray-500">Metode:</span> <span class="font-medium">{{ $kerjasama->metode->nama_metode }}</span></div>
            @endif
            @if($kerjasama->implementasi)
            <div><span class="text-gray-500">Implementasi:</span> <span class="font-medium">{{ $kerjasama->implementasi->nama_status }}</span></div>
            @endif
            @if($kerjasama->nomor_pihak1 || $kerjasama->nomor_pihak2)
            <div><span class="text-gray-500">Nomor Pihak 1:</span> <span class="font-medium">{{ $kerjasama->nomor_pihak1 ?? '-' }}</span></div>
            <div><span class="text-gray-500">Nomor Pihak 2:</span> <span class="font-medium">{{ $kerjasama->nomor_pihak2 ?? '-' }}</span></div>
            @endif
        </div>
        @if(!$kerjasama->ks_metode && !$kerjasama->jangka_waktu_thn)
        <p class="text-xs text-yellow-600 mt-3">Menunggu finalisasi data oleh Admin Pusdatin.</p>
        @endif
    </div>
    @endif

    <!-- Detail Data -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-gray-800">Informasi Kerja Sama</h3>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
            <div><span class="text-gray-500">Jenis:</span> <span class="font-medium">{{ $kerjasama->jenis?->nama_jenis }}</span></div>
            <div><span class="text-gray-500">Tingkat:</span> <span class="font-medium">{{ $kerjasama->tingkat?->nama_tingkat }}</span></div>
            <div><span class="text-gray-500">Provinsi:</span> <span class="font-medium">{{ $kerjasama->provinsi ?? '-' }}</span></div>
            <div><span class="text-gray-500">Kode Wilayah:</span> <span class="font-medium">{{ $kerjasama->kode_wilayah ?? '-' }}</span></div>
            <div><span class="text-gray-500">Instansi:</span> <span class="font-medium">{{ $kerjasama->nama_kl ?? '-' }}</span></div>
            <div><span class="text-gray-500">Jml K/L:</span> <span class="font-medium">{{ $kerjasama->jumlah_kl_terlibat }}</span></div>
            <div class="sm:col-span-2 lg:col-span-3">
                <span class="text-gray-500">Tentang:</span> <span class="font-medium">{{ $kerjasama->tentang ?? '-' }}</span>
            </div>
            <div><span class="text-gray-500">Pihak 1:</span> <span class="font-medium">{{ $kerjasama->pihak1 ?? '-' }}</span></div>
            <div><span class="text-gray-500">Pihak 2:</span> <span class="font-medium">{{ $kerjasama->pihak2 ?? '-' }}</span></div>
            <div><span class="text-gray-500">Jangka Waktu:</span> <span class="font-medium">{{ $kerjasama->jangka_waktu_thn ? $kerjasama->jangka_waktu_thn.' tahun' : '-' }}</span></div>
            <div><span class="text-gray-500">Tgl Mulai:</span> <span class="font-medium">{{ $kerjasama->tanggal_mulai_ks?->format('d M Y') ?? '-' }}</span></div>
            <div><span class="text-gray-500">Tgl Berakhir:</span> <span class="font-medium">{{ $kerjasama->tanggal_selesai_ks?->format('d M Y') ?? '-' }}</span></div>
            @if($kerjasama->sisa_masa_berlaku)
                <div><span class="text-gray-500">Sisa Masa Berlaku:</span> <span class="font-medium">{{ $kerjasama->sisa_masa_berlaku }}</span></div>
            @elseif($kerjasama->sisa_masa_berlaku_hari !== null)
                <div><span class="text-gray-500">Sisa Masa Berlaku:</span> <span class="font-medium {{ $kerjasama->sisa_masa_berlaku_hari < 30 ? 'text-red-600' : '' }}">{{ $kerjasama->sisa_masa_berlaku_hari }} hari</span></div>
            @endif
            <div><span class="text-gray-500">Narahubung Adm:</span> <span class="font-medium">{{ $kerjasama->narahubung_adm ?? '-' }}</span></div>
            <div><span class="text-gray-500">Kontak Adm:</span> <span class="font-medium">{{ $kerjasama->nomor_cp_adm ?? '-' }}</span></div>
            <div><span class="text-gray-500">Narahubung Teknis:</span> <span class="font-medium">{{ $kerjasama->narahubung_teknis ?? '-' }}</span></div>
            <div><span class="text-gray-500">Kontak Teknis:</span> <span class="font-medium">{{ $kerjasama->nomor_cp_teknis ?? '-' }}</span></div>
            @if($kerjasama->metode)
                <div><span class="text-gray-500">Metode:</span> <span class="font-medium">{{ $kerjasama->metode->nama_metode }}</span></div>
            @endif
            @if($kerjasama->implementasi)
                <div><span class="text-gray-500">Implementasi:</span> <span class="font-medium">{{ $kerjasama->implementasi->nama_status }}</span></div>
            @endif
        </div>
    </div>

    <!-- Dokumen -->
    @php $files = $kerjasama->dokumen_ks ? (json_decode($kerjasama->dokumen_ks, true) ?: []) : []; @endphp
    @if(count($files))
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-gray-800">Dokumen</h3>
        </div>
        <div class="p-6">
            <ul class="divide-y divide-gray-100">
                @foreach($files as $i => $file)
                @php
                    $labels = ['Surat Permohonan', 'Draft Nota Kesepakatan', 'Dokumen Bertanda Tangan'];
                    $label = $labels[$i] ?? 'Dokumen Revisi ke-'.($i - 2);
                @endphp
                <li class="flex items-center gap-2 py-2">
                    <span class="text-sm">{{ $label }}</span>
                    <span class="text-gray-300">—</span>
                    <a href="{{ Storage::disk('public')->url($file) }}" class="text-primary text-xs hover:underline" target="_blank">Lihat</a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Review Logs -->
    @php $reviewLogs = $kerjasama->review_log; @endphp
    @if(count($reviewLogs))
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-3 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-navy">Riwayat Review</h3>
        </div>
        <div class="px-5 py-3 space-y-2">
            @foreach($reviewLogs as $log)
            @php
                $status = $log['status'] ?? '';
                $label = \App\Services\WorkflowService::STATUS[$status] ?? $status;
                $waktu = \Carbon\Carbon::parse($log['waktu'] ?? '')->format('d M Y, H:i');
                $isReject = $status === 'DITOLAK';
            @endphp
            <div class="flex gap-2 text-xs">
                <span class="{{ $isReject ? 'text-red-600' : 'text-green-600' }} font-medium flex-shrink-0">{{ $label }}</span>
                <span class="text-gray-400">{{ $waktu }}</span>
                @if($log['alasan'] ?? null)
                <span class="text-gray-500">— {{ $log['alasan'] }}</span>
                @endif
                @if($log['catatan'] ?? null)
                <span class="text-gray-400">({{ $log['catatan'] }})</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

    <!-- Upload Dokumen (langsung tampil, bukan modal) -->
    @if($kerjasama->ks_jenis == 3 && in_array($kerjasama->status_pengajuan, ['DRAFT', 'UPLOAD_DOKUMEN']))
    <div class="bg-white rounded-lg shadow-sm border-2 border-dashed border-primary/30 p-6">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
            <h3 class="text-base font-semibold text-navy">Upload Dokumen Nota Kesepakatan</h3>
        </div>
        <p class="text-sm text-gray-500 mb-4">Upload dua dokumen yang diperlukan: Surat Permohonan dan Draft Nota Kesepakatan.</p>
        <form action="{{ route('mitra.kerjasama.upload', $kerjasama->kerjasama_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">1. Surat Permohonan <span class="text-red-500">*</span></label>
                    <input type="file" name="surat_permohonan" accept=".pdf,.docx,.zip" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <p class="text-xs text-gray-400 mt-1">Surat dari Kepala Daerah ke Sekjen Kemendikdasmen</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">2. Draft Nota Kesepakatan <span class="text-red-500">*</span></label>
                    <input type="file" name="draft_nk" accept=".pdf,.docx,.zip" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <p class="text-xs text-gray-400 mt-1">Draft NK yang akan dibahas bersama</p>
                </div>
            </div>
            <p class="text-xs text-gray-400 mb-4">Format: PDF, DOCX, ZIP — Maks 20MB per file</p>
            <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-600">
                Upload Dokumen
            </button>
        </form>
    </div>
    @endif
@endsection
