@extends('layouts.app')

@section('title', 'Review Pengajuan - Admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <header class="bg-navy px-6 py-4 flex justify-between items-center shadow-md sticky top-0 z-10">
        <div class="flex items-center gap-3">
            <img src="https://pelayanan.data.kemendikdasmen.go.id/assets/imge/tutwuri.png" class="h-8" alt="">
            <div>
                <h1 class="text-lg font-semibold text-white">Detail Pengajuan</h1>
                <p class="text-xs text-white/50">Admin Pusdatin</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.review.index') }}" class="text-white/70 text-sm hover:text-white">&larr; Kembali</a>
            <span class="text-xs text-white bg-primary/80 px-3 py-1.5 rounded-full font-medium">Admin</span>
            <a href="/" class="text-white/70 text-sm hover:text-red-300">Keluar</a>
        </div>
    </header>

    <div class="px-6 py-6 max-w-5xl mx-auto space-y-6">
        <x-workflow-stepper :current="$kerjasama->status_pengajuan" />

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-navy mb-3">Data Pengajuan</h3>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div><span class="text-gray-500">Mitra:</span> <span class="font-medium">{{ $kerjasama->nama_kl }}</span></div>
                <div><span class="text-gray-500">Jenis:</span> <span class="font-medium">{{ $kerjasama->jenis?->nama_jenis }}</span></div>
                <div><span class="text-gray-500">Tingkat:</span> <span class="font-medium">{{ $kerjasama->tingkat?->nama_tingkat }}</span></div>
                <div><span class="text-gray-500">Provinsi:</span> <span class="font-medium">{{ $kerjasama->provinsi ?? '-' }}</span></div>
                <div class="col-span-2"><span class="text-gray-500">Tentang:</span> <span class="font-medium">{{ $kerjasama->tentang }}</span></div>
                <div><span class="text-gray-500">Pihak 1:</span> <span class="font-medium">{{ $kerjasama->pihak1 }}</span></div>
                <div><span class="text-gray-500">Pihak 2:</span> <span class="font-medium">{{ $kerjasama->pihak2 }}</span></div>
                <div><span class="text-gray-500">Jangka Waktu:</span> <span class="font-medium">{{ $kerjasama->jangka_waktu_thn }} tahun</span></div>
                <div><span class="text-gray-500">Tgl Mulai:</span> <span class="font-medium">{{ $kerjasama->tanggal_mulai_ks?->format('d M Y') }}</span></div>
                <div><span class="text-gray-500">Tgl Berakhir:</span> <span class="font-medium">{{ $kerjasama->tanggal_selesai_ks?->format('d M Y') }}</span></div>
                <div><span class="text-gray-500">Status:</span> <x-status-badge :status="$kerjasama->status_pengajuan" :label="$kerjasama->status_label" /></div>
            </div>
        </div>

        <!-- Dokumen -->
        @php
            $dokFiles = json_decode($kerjasama->dokumen_ks ?? '[]', true) ?: [];
            $dokLabels = ['Surat Permohonan', 'Draft Nota Kesepakatan', 'Dokumen Bertanda Tangan'];
        @endphp
        @if(!empty($dokFiles))
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-navy mb-3">Dokumen</h3>
            <ul class="divide-y divide-gray-100">
                @foreach($dokFiles as $i => $file)
                <li class="flex items-center justify-between py-2.5">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $dokLabels[$i] ?? 'Dokumen Revisi ke-'.($i - 2) }}</p>
                            <p class="text-xs text-gray-400 truncate max-w-xs">{{ basename($file) }}</p>
                        </div>
                    </div>
                    <a href="{{ Storage::disk('public')->url($file) }}" target="_blank" class="text-primary text-xs hover:underline flex-shrink-0">Lihat</a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- DIAJUKAN: Approve / Reject -->
        @if($kerjasama->status_pengajuan === 'DIAJUKAN')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6" x-data="{ confirmApprove: false, rejectOpen: false }">
            <h3 class="text-base font-semibold text-navy mb-4">Tindakan Admin</h3>
            <div class="flex gap-4">
                <button type="button" @click="confirmApprove = true" class="bg-green-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700">
                    Setujui
                </button>
                <button @click="rejectOpen = true" class="bg-red-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-red-700">
                    Tolak
                </button>
            </div>

            <!-- Modal Setujui -->
            <div x-show="confirmApprove" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5)">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6" @click.outside="confirmApprove = false">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Setujui Pengajuan?</h3>
                            <p class="text-sm text-gray-500">Pengajuan akan dilanjutkan ke tahap penjadwalan pembahasan.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button @click="confirmApprove = false" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Batal</button>
                        <form action="{{ route('admin.review.approve', $kerjasama->kerjasama_id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">Ya, Setujui</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Tolak -->
            <div x-show="rejectOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5)">
                <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6" @click.outside="rejectOpen = false">
                    <h3 class="text-lg font-semibold text-navy mb-4">Tolak Pengajuan</h3>
                    <form action="{{ route('admin.review.reject', $kerjasama->kerjasama_id) }}" method="POST">
                        @csrf
                        <textarea name="alasan" rows="3" required placeholder="Alasan penolakan..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-3"></textarea>
                        <textarea name="catatan_perbaikan" rows="3" placeholder="Catatan perbaikan (opsional)" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-4"></textarea>
                        <div class="flex gap-3">
                            <button type="submit" class="flex-1 bg-red-600 text-white py-2.5 rounded-lg text-sm hover:bg-red-700">Tolak</button>
                            <button type="button" @click="rejectOpen = false" class="flex-1 border border-gray-300 text-gray-700 py-2.5 rounded-lg text-sm hover:bg-gray-50">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- DISETUJUI: Jadwalkan -->
        @if($kerjasama->status_pengajuan === 'DISETUJUI')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-navy mb-4">Jadwalkan Pembahasan</h3>
            <form action="{{ route('admin.pembahasan.schedule', $kerjasama->kerjasama_id) }}" method="POST" class="grid grid-cols-2 gap-3">
                @csrf
                <div><label class="text-sm">Tanggal</label><input type="date" name="tanggal_pembahasan_ks" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm"></div>
                <div><label class="text-sm">Jam</label><input type="time" name="jam_pembahasan" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm"></div>
                <div class="col-span-2"><label class="text-sm">Lokasi</label><input type="text" name="lokasi_pembahasan" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm"></div>
                <div><label class="text-sm">Link Meeting</label><input type="text" name="link_meeting" class="w-full border border-gray-300 rounded px-3 py-2 text-sm"></div>
                <div><label class="text-sm">PIC</label><input type="text" name="pic_pembahasan" class="w-full border border-gray-300 rounded px-3 py-2 text-sm"></div>
                <div class="col-span-2"><button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-600">Simpan Jadwal</button></div>
            </form>
        </div>
        @endif

        <!-- MENUNGGU_PEMBAHASAN: Complete -->
        @if($kerjasama->status_pengajuan === 'MENUNGGU_PEMBAHASAN')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-navy mb-4">Selesaikan Pembahasan</h3>
            <form action="{{ route('admin.pembahasan.complete', $kerjasama->kerjasama_id) }}" method="POST" class="grid grid-cols-2 gap-3">
                @csrf
                <div><label class="text-sm">Nomor Pihak 1</label><input type="text" name="nomor_pihak1" class="w-full border border-gray-300 rounded px-3 py-2 text-sm"></div>
                <div><label class="text-sm">Nomor Pihak 2</label><input type="text" name="nomor_pihak2" class="w-full border border-gray-300 rounded px-3 py-2 text-sm"></div>
                <div class="col-span-2"><button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-600">Selesai Pembahasan</button></div>
            </form>
        </div>
        @endif

        <!-- SELESAI_PEMBAHASAN: Upload Dokumen Bertanda Tangan -->
        @if($kerjasama->status_pengajuan === 'SELESAI_PEMBAHASAN')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6" x-data="{ confirmTtd: false }">
            <h3 class="text-base font-semibold text-navy mb-1">Selesaikan Penandatanganan</h3>
            <p class="text-xs text-gray-500 mb-4">Upload dokumen NK final yang sudah ditandatangani oleh kedua pihak, lalu lengkapi informasi penandatanganan.</p>
            <form action="{{ route('admin.pembahasan.complete-ttd', $kerjasama->kerjasama_id) }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="ttdForm">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dokumen Bertanda Tangan <span class="text-red-500">*</span></label>
                    <input type="file" name="dokumen_ttd" accept=".pdf,.docx,.zip" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <p class="text-xs text-gray-400 mt-1">Dokumen NK final yang sudah di-TTD kedua pihak. PDF/DOCX/ZIP, maks 20MB.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Penandatanganan <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_ttd" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penandatangan Pihak 1</label>
                        <input type="text" name="ttd_pihak1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Nama penandatangan">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penandatangan Pihak 2</label>
                        <input type="text" name="ttd_pihak2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Nama penandatangan">
                    </div>
                </div>
                <button type="button" @click="confirmTtd = true" class="bg-green-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700">
                    Selesai Penandatanganan
                </button>
            </form>

            <!-- Modal Konfirmasi TTD -->
            <div x-show="confirmTtd" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5)">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6" @click.outside="confirmTtd = false">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Selesaikan TTD?</h3>
                            <p class="text-sm text-gray-500">Status akan berubah menjadi Selesai.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button @click="confirmTtd = false" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Batal</button>
                        <button type="submit" form="ttdForm" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">Ya, Selesaikan</button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Review Log -->
        @php $reviewLogs = $kerjasama->review_log; @endphp
        @if(count($reviewLogs))
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-navy mb-4">Riwayat Aktivitas</h3>
            <div class="space-y-3">
                @foreach($reviewLogs as $log)
                @php
                    $isReject = ($log['status'] ?? '') === 'DITOLAK';
                    $isApprove = ($log['status'] ?? '') === 'DISETUJUI';
                    $isSchedule = ($log['status'] ?? '') === 'JADWAL';
                    $bg = $isReject ? '#fef2f2' : ($isApprove ? '#f0fdf4' : ($isSchedule ? '#eff6ff' : '#f9fafb'));
                    $text = $isReject ? '#991b1b' : ($isApprove ? '#166534' : ($isSchedule ? '#1e40af' : '#374151'));
                    $icon = $isReject ? '✗' : ($isApprove ? '✓' : ($isSchedule ? '📅' : '•'));
                @endphp
                <div class="flex gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm" style="background:{{ $bg }};color:{{ $text }}">
                        {{ $icon }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">
                            {{ \App\Services\WorkflowService::STATUS[$log['status'] ?? ''] ?? $log['status'] ?? '-' }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $log['alasan'] ?? '-' }}</p>
                        @if($log['catatan'] ?? null)
                        <p class="text-xs text-gray-400 mt-0.5">{{ $log['catatan'] }}</p>
                        @endif
                        <p class="text-xs text-gray-400 mt-0.5">{{ $log['waktu'] ?? '' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- SELESAI: Finalisasi -->
        @if($kerjasama->status_pengajuan === 'SELESAI')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-navy mb-4">Finalisasi Data Kerja Sama</h3>
            <form action="{{ route('admin.pembahasan.metode', $kerjasama->kerjasama_id) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="text-sm">Jangka Waktu (tahun)</label>
                        <input type="number" name="jangka_waktu_thn" value="{{ $kerjasama->jangka_waktu_thn }}" min="1" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="text-sm">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai_ks" value="{{ $kerjasama->tanggal_mulai_ks?->format('Y-m-d') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="text-sm">Tanggal Berakhir</label>
                        <input type="date" name="tanggal_selesai_ks" value="{{ $kerjasama->tanggal_selesai_ks?->format('Y-m-d') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm">Metode Pemanfaatan Data</label>
                        <select name="ks_metode" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="">Pilih</option>
                            @foreach(\App\Models\KsMetode::all() as $m)
                                <option value="{{ $m->id }}" {{ $kerjasama->ks_metode == $m->id ? 'selected' : '' }}>{{ $m->nama_metode }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm">Status Implementasi</label>
                        <select name="ks_implementasi" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="">Pilih</option>
                            @foreach(\App\Models\KsImplementasi::all() as $imp)
                                <option value="{{ $imp->id }}" {{ $kerjasama->ks_implementasi == $imp->id ? 'selected' : '' }}>{{ $imp->nama_status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm">Data yang Dikirim Pusdatin</label>
                        <textarea name="pusdatin_kirim_data" rows="2" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">{{ $kerjasama->pusdatin_kirim_data }}</textarea>
                    </div>
                    <div>
                        <label class="text-sm">Data yang Diterima Pusdatin</label>
                        <textarea name="pusdatin_terima_data" rows="2" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">{{ $kerjasama->pusdatin_terima_data }}</textarea>
                    </div>
                </div>
                <div><button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-600">Simpan</button></div>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
