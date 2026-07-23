@extends('layouts.mitra')

@section('title', 'Detail Kerja Sama')
@section('page-title', 'Detail Kerja Sama')

@section('page-content')
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
@endif
@if(session('info'))
<div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('info') }}</div>
@endif
@if(session('error'))
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('error') }}</div>
@endif

<div class="space-y-6" x-data="{ confirmAjukan: false }">
    @php
        $reviewLogs = $kerjasama->review_log;
        $lastReject = collect($reviewLogs)->filter(fn($l) => ($l['label'] ?? '') === 'Ditolak')->last();
        $isRejected = $lastReject && !$kerjasama->ks_status_dok;
        $status = (int) $kerjasama->ks_status_dok;
        $hasJadwal = (bool) $kerjasama->tanggal_pembahasan;
    @endphp

    <!-- Progress Stepper -->
    @php
        $currentStep = 1;
        if ($isRejected) {
            $currentStep = 0;
        } elseif ($status === 1) {
            $currentStep = 2;
        } elseif ($status === 2 && !$hasJadwal) {
            $currentStep = 3;
        } elseif ($status === 2 && $hasJadwal && !$kerjasama->hasSuratUndangan()) {
            $currentStep = 4;
        } elseif ($status === 2 && $hasJadwal && $kerjasama->hasSuratUndangan()) {
            $currentStep = 5;
        } elseif ($status === 3) {
            $currentStep = 5;
        } elseif ($status === 4) {
            $currentStep = 6;
        } elseif ($status >= 5) {
            $currentStep = 7;
        }
        $steps = [
            1 => ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Lengkapi Data', 'desc' => 'Upload dokumen & ajukan'],
            2 => ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', 'label' => 'Review Admin', 'desc' => 'Admin memeriksa pengajuan'],
            3 => ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Penjadwalan', 'desc' => 'Admin menjadwalkan pembahasan'],
            4 => ['icon' => 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12', 'label' => 'Upload Undangan', 'desc' => 'Upload surat undangan'],
            5 => ['icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Pembahasan', 'desc' => 'Proses pembahasan dokumen'],
            6 => ['icon' => 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z', 'label' => 'Penandatanganan', 'desc' => 'Proses TTD para pihak'],
            7 => ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Dokumen telah ditandatangani (Tahap Integrasi Data)', 'desc' => 'TTD selesai & Atur Integrasi'],
        ];
    @endphp

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-navy mb-4 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Status Proses
        </h3>

        @if($isRejected)
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p class="text-sm font-semibold text-red-700">Pengajuan Ditolak</p>
                <p class="text-sm text-red-600 mt-1"><span class="font-medium">Catatan Penolakan:</span> {{ $lastReject['catatan'] ?? $lastReject['alasan'] ?? 'Silakan perbaiki dokumen dan ajukan ulang.' }}</p>
            </div>
        </div>
        @endif

        <div class="space-y-0">
            @foreach($steps as $num => $step)
                @php
                    $isCompleted = $currentStep > $num;
                    $isActive = $currentStep === $num;
                    $isPending = $currentStep < $num;
                @endphp
                <div class="flex gap-3">
                    <div class="flex flex-col items-center shrink-0">
                        @if($isCompleted)
                            <div class="w-7 h-7 rounded-full bg-green-500 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        @elseif($isActive)
                            <div class="w-7 h-7 rounded-full bg-primary flex items-center justify-center ring-4 ring-primary/20">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{!! $step['icon'] !!}"/></svg>
                            </div>
                        @else
                            <div class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center">
                                <span class="text-xs font-semibold text-gray-400">{{ $num }}</span>
                            </div>
                        @endif
                        @if($num < 7)
                            <div class="w-0.5 flex-1 {{ $isCompleted ? 'bg-green-300' : 'bg-gray-200' }} my-0.5"></div>
                        @endif
                    </div>
                    <div class="{{ $num === 7 ? '' : 'pb-3' }}">
                        <p class="text-sm font-medium {{ $isCompleted ? 'text-green-700' : ($isActive ? 'text-primary' : 'text-gray-400') }}">{{ $step['label'] }}</p>
                        <p class="text-xs {{ $isActive ? 'text-gray-500' : 'text-gray-400' }}">{{ $step['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Action Buttons -->
    @if($kerjasama->ks_jenis == 3 && !$kerjasama->ks_status_dok)
    <div class="flex gap-2">
        <a href="{{ route('mitra.kerjasama.edit', $kerjasama->kerjasama_id) }}" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Edit</a>
        <button type="button" @click="confirmAjukan = true" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">Ajukan ke Admin</button>
    </div>
    @endif

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

    <!-- Jadwal Pembahasan -->
    @if($kerjasama->tanggal_pembahasan && $kerjasama->ks_status_dok < 4)
    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg shadow-sm border-2 border-blue-300 p-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div class="flex-1">
                <p class="text-xs font-medium text-blue-500 uppercase tracking-wider mb-1">Jadwal Pembahasan</p>
                <p class="text-xl font-bold text-blue-800">{{ \Carbon\Carbon::parse($kerjasama->tanggal_pembahasan)->format('d M Y') }}</p>
                <p class="text-lg text-blue-700">{{ \Carbon\Carbon::parse($kerjasama->tanggal_pembahasan)->format('H:i') }} WIB</p>
                @if($kerjasama->ks_status_dok == 2 && !$kerjasama->hasSuratUndangan())
                <p class="text-sm text-blue-600 mt-2 pt-2 border-t border-blue-300">Silakan upload surat undangan sesuai tanggal di atas.</p>
                @endif
                <p class="text-xs text-amber-600 mt-2 font-medium">Jika jadwal kurang sesuai, silakan hubungi Admin Pusdatin untuk penyesuaian.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Upload Surat Undangan (Status 2, sudah ada jadwal) -->
    @if($kerjasama->ks_status_dok == 2 && $kerjasama->tanggal_pembahasan && !$kerjasama->hasSuratUndangan())
    <div class="bg-white rounded-lg shadow-sm border-2 border-dashed border-primary/30 p-6">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
            <h3 class="text-base font-semibold text-navy">Upload Surat Undangan</h3>
        </div>
        <p class="text-sm text-gray-500 mb-4">Pengajuan telah disetujui. Silakan upload surat undangan pembahasan NK untuk melanjutkan ke tahap pembahasan.</p>
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

        <!-- Integration Catalog Section -->
        <div class="mt-6 pt-6 border-t border-gray-100">
            @php
                // Resolve user
                $user = auth()->user() ?? \App\Models\User::where('role', 'mitra')->first() ?? \App\Models\User::first();
                $userSelections = \App\Models\MetadataUser::with('metadata')
                    ->where('user_id', $user->id)
                    ->where('soft_delete', 0)
                    ->get();
                $groupedUserSelections = $userSelections->groupBy(function($item) {
                    return $item->metadata->tbl_name ?? 'Lainnya';
                });
                $hasSelections = $userSelections->count() > 0;
            @endphp

            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <h4 class="text-sm font-bold text-gray-800">Data Integrasi Pusdatin yang Diajukan</h4>
                    </div>
                </div>

                @if(!$hasSelections)
                    <p class="text-xs text-gray-600 mb-4">Silakan pilih tabel dan kolom data dari Katalog Pusdatin yang Anda butuhkan untuk mengaktifkan integrasi sistem Anda.</p>
                    <a href="{{ route('mitra.kerjasama.integrasi', $kerjasama->kerjasama_id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-blue-600 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                        Pilih Katalog Data &rarr;
                    </a>
                @else
                    <p class="text-xs text-gray-600 mb-4">Pengajuan integrasi data Anda telah aktif! Berikut adalah ringkasan skema tabel yang dapat diakses oleh sistem Anda:</p>
                    
                    <div class="bg-white rounded-lg border border-gray-200 divide-y divide-gray-100 overflow-hidden mb-4">
                        @foreach($groupedUserSelections as $tblName => $items)
                        <div class="p-3.5">
                            <div class="flex items-center space-x-2 mb-1.5">
                                <span class="text-[10px] px-1.5 py-0.5 bg-blue-100 text-blue-800 font-mono font-semibold rounded">Tabel</span>
                                <span class="text-xs font-bold font-mono text-gray-800">{{ $tblName }}</span>
                            </div>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($items as $item)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono border bg-gray-50 text-gray-700 border-gray-200">
                                    {{ $item->metadata->name }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <a href="{{ route('mitra.kerjasama.integrasi', $kerjasama->kerjasama_id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-lg shadow-sm transition">
                        Ubah Pilihan Katalog Data
                    </a>
                @endif
            </div>
        </div>
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
    @php $files = $kerjasama->folder_ks ? (json_decode($kerjasama->folder_ks, true) ?: []) : []; @endphp
    @if(count($files))
    <div class="bg-white rounded-lg shadow-sm border border-gray-200" x-data="{ open: false, src: '' }">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-gray-800">Dokumen</h3>
        </div>
        <div class="p-6">
            <ul class="divide-y divide-gray-100">
                @foreach($files as $i => $file)
                @php
                    $labels = ['Surat Permohonan', 'Draft Nota Kesepakatan', 'Surat Undangan'];
                    $label = $labels[$i] ?? 'Dokumen ke-'.($i + 1);
                @endphp
                <li class="flex items-center gap-2 py-2">
                    <span class="text-sm">{{ $label }}</span>
                    <span class="text-gray-300">—</span>
                    <button type="button" @click="open = true; src = '{{ Storage::disk('public')->url($file) }}'" class="text-primary text-xs hover:underline">Lihat</button>
                </li>
                @endforeach
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
    <div class="bg-white rounded-lg shadow-sm border border-gray-200" x-data="{ openRiwayat: false }">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between cursor-pointer" @click="openRiwayat = !openRiwayat">
            <h3 class="text-sm font-semibold text-navy">Riwayat Review</h3>
            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="openRiwayat ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
        <div class="px-5 py-4" x-show="openRiwayat">
            @foreach($reviewLogs as $i => $log)
            @php
                $label = $log['label'] ?? $log['status'] ?? '';
                $waktu = \Carbon\Carbon::parse($log['waktu'] ?? '')->format('d M Y, H:i');
                $catatan = $log['catatan'] ?? null;
                if ($catatan) {
                    $catatan = preg_replace_callback('/\b(\d{4}-\d{2}-\d{2}[T ]\d{2}:\d{2}(?::\d{2})?)\b/', function($m) {
                        try {
                            return \Illuminate\Support\Carbon::parse($m[1])->format('d M Y, H:i');
                        } catch (\Throwable $e) {
                            return $m[1];
                        }
                    }, $catatan);
                }
                $last = $i === count($reviewLogs) - 1;
                $badgeClass = match($label) {
                    'Ditolak' => 'bg-red-100 text-red-700',
                    'Disetujui' => 'bg-green-100 text-green-700',
                    'Jadwal' => 'bg-blue-100 text-blue-700',
                    'TTD Selesai' => 'bg-purple-100 text-purple-700',
                    'Finalisasi' => 'bg-violet-100 text-violet-700',
                    default => 'bg-gray-100 text-gray-600',
                };
                $dotColor = match($label) {
                    'Ditolak' => 'bg-red-500',
                    'Disetujui' => 'bg-green-500',
                    'Jadwal' => 'bg-blue-500',
                    'TTD Selesai' => 'bg-purple-500',
                    'Finalisasi' => 'bg-violet-500',
                    default => 'bg-gray-400',
                };
            @endphp
            <div class="flex gap-3">
                <div class="flex flex-col items-center flex-shrink-0">
                    <div class="w-2.5 h-2.5 rounded-full {{ $dotColor }} mt-1"></div>
                    @if(!$last)
                    <div class="w-0.5 flex-1 bg-gray-200 my-0.5"></div>
                    @endif
                </div>
                <div class="{{ $last ? '' : 'pb-3' }}">
                    <span class="inline-block px-2 py-0.5 rounded-full font-medium text-[11px] {{ $badgeClass }}">{{ $label }}</span>
                    <span class="text-gray-400 text-[11px] ml-1.5">{{ $waktu }}</span>
                    @if($catatan)
                    <p class="text-gray-500 text-xs mt-1">{{ $catatan }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

    <!-- Upload Dokumen (langsung tampil, bukan modal) -->
    @if($kerjasama->ks_jenis == 3 && !$kerjasama->ks_status_dok && !$kerjasama->folder_ks)
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
