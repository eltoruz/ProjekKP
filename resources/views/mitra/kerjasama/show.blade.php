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

<div class="space-y-6" x-data="{ confirmAjukan: false, confirmHapus: false }">
    @php
        $reviewLogs = $kerjasama->review_log;
        $lastReject = collect($reviewLogs)->filter(fn($l) => ($l['label'] ?? '') === 'Ditolak')->last();
        $isRejected = $lastReject && !$kerjasama->ks_status_dok;
    @endphp

    @if($isRejected)
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="flex-1">
                <h4 class="text-sm font-semibold text-red-700">Pengajuan Ditolak</h4>
                <p class="text-sm text-red-600 mt-1">{{ $lastReject['alasan'] ?? 'Tanpa alasan' }}</p>
                @if($lastReject['catatan'] ?? null)
                <p class="text-xs text-red-500 mt-1">Catatan: {{ $lastReject['catatan'] }}</p>
                @endif
                @if($lastReject['waktu'] ?? null)
                <p class="text-xs text-red-400 mt-1">{{ \Carbon\Carbon::parse($lastReject['waktu'])->format('d M Y, H:i') }}</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Status Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            @if($kerjasama->ks_status_dok)
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500">Status:</span>
                <x-status-badge :status="$kerjasama->ks_status_dok" :label="$kerjasama->status_label" />
            </div>
            @endif
            @if($kerjasama->ks_jenis == 3)
            <div class="flex gap-2">
                @if(!$kerjasama->ks_status_dok)
                    <a href="{{ route('mitra.kerjasama.edit', $kerjasama->kerjasama_id) }}" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Edit</a>
                    <button type="button" @click="confirmHapus = true" class="border border-red-300 text-red-600 px-4 py-2 rounded-lg text-sm hover:bg-red-50">Hapus</button>
                    <button type="button" @click="confirmAjukan = true" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">Ajukan ke Admin</button>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Modal Konfirmasi Ajukan -->
    <div x-show="confirmAjukan" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5)">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6" @click.outside="confirmAjukan = false">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Ajukan ke Admin?</h3>
                    <p class="text-sm text-gray-500">Setelah diajukan, data tidak dapat diubah lagi.</p>
                </div>
            </div>
            <div class="flex gap-3 justify-end">
                <button @click="confirmAjukan = false" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Batal</button>
                <form action="{{ route('mitra.kerjasama.ajukan', $kerjasama->kerjasama_id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">Ya, Ajukan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div x-show="confirmHapus" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5)">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6" @click.outside="confirmHapus = false">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Hapus Draft?</h3>
                    <p class="text-sm text-gray-500">Data yang dihapus tidak dapat dikembalikan.</p>
                </div>
            </div>
            <div class="flex gap-3 justify-end">
                <button @click="confirmHapus = false" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Batal</button>
                <form action="{{ route('mitra.kerjasama.destroy', $kerjasama->kerjasama_id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Upload Surat Undangan (Status 2 = Disetujui, menunggu undangan) -->
    @if($kerjasama->ks_status_dok == 2 && !$kerjasama->surat_undangan)
    <div class="bg-white rounded-lg shadow-sm border-2 border-dashed border-primary/30 p-6">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
            <h3 class="text-base font-semibold text-navy">Upload Surat Undangan</h3>
        </div>
        <p class="text-sm text-gray-500 mb-4">Pengajuan telah disetujui. Silakan upload surat undangan pembahasan NK untuk melanjutkan ke tahap penjadwalan.</p>
        <form action="{{ route('mitra.kerjasama.upload-undangan', $kerjasama->kerjasama_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Surat Undangan <span class="text-red-500">*</span></label>
                <input type="file" name="surat_undangan" accept=".pdf,.docx,.zip" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <p class="text-xs text-gray-400 mt-1">Format: PDF, DOCX, ZIP — Maks 20MB</p>
            </div>
            <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-600">
                Upload Surat Undangan
            </button>
        </form>
    </div>
    @endif

    @if($kerjasama->ks_status_dok == 2 && $kerjasama->surat_undangan && !$kerjasama->tanggal_pembahasan)
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-sm font-medium text-green-700">Surat Undangan Terupload</p>
                <p class="text-xs text-green-600 mt-0.5">Menunggu admin menjadwalkan pembahasan.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Jadwal Pembahasan -->
    @if($kerjasama->tanggal_pembahasan)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-base font-semibold text-navy mb-3">Jadwal Pembahasan</h3>
        <div class="text-sm">
            <div><span class="text-gray-500">Tanggal & Waktu:</span> <span class="font-medium">{{ \Carbon\Carbon::parse($kerjasama->tanggal_pembahasan)->format('d M Y, H:i') }}</span></div>
        </div>
    </div>
    @endif

    <!-- Data Final (setelah TTD) -->
    @if($kerjasama->ks_status_dok == 5)
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
            @if($kerjasama->sisa_masa_berlaku_hari !== null)
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
    @if(count($files) || $kerjasama->surat_undangan)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200" x-data="{ open: false, src: '' }">
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
                    <button type="button" @click="open = true; src = '{{ Storage::disk('public')->url($file) }}'" class="text-primary text-xs hover:underline">Lihat</button>
                </li>
                @endforeach
                @if($kerjasama->surat_undangan)
                <li class="flex items-center gap-2 py-2">
                    <span class="text-sm">Surat Undangan</span>
                    <span class="text-gray-300">—</span>
                    <button type="button" @click="open = true; src = '{{ Storage::disk('public')->url($kerjasama->surat_undangan) }}'" class="text-primary text-xs hover:underline">Lihat</button>
                </li>
                @endif
            </ul>
        </div>

        <!-- Modal -->
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

    <!-- Review Logs -->
    @if(count($reviewLogs))
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-3 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-navy">Riwayat Review</h3>
        </div>
        <div class="px-5 py-3 space-y-2">
            @foreach($reviewLogs as $log)
            @php
                $label = $log['label'] ?? $log['status'] ?? '';
                $waktu = \Carbon\Carbon::parse($log['waktu'] ?? '')->format('d M Y, H:i');
                $isReject = ($log['label'] ?? '') === 'Ditolak';
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
    @if($kerjasama->ks_jenis == 3 && !$kerjasama->ks_status_dok && !$kerjasama->dokumen_ks)
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
