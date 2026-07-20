@extends('layouts.admin')

@section('title', 'Review Kerja Sama')
@section('page-title', 'Review: ' . $ks->nama_kl)

@section('page-content')
@php
    $status = (int) $ks->ks_status_dok;
    $hasJadwal = (bool) $ks->tanggal_pembahasan;
    $waitingUndangan = $status === 2 && $hasJadwal && !$ks->hasSuratUndangan();
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
        <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
            <h3 class="text-sm font-semibold text-slate-800">Status Dokumen</h3>
        </div>
        <div class="px-5 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                @php
                    $badgeClass = match((int)$ks->ks_status_dok) {
                        1 => 'bg-blue-100 text-blue-700',
                        2 => 'bg-yellow-100 text-yellow-700',
                        3 => 'bg-orange-100 text-orange-700',
                        4 => 'bg-purple-100 text-purple-700',
                        5 => 'bg-green-100 text-green-700',
                        6 => 'bg-gray-100 text-gray-600',
                        default => 'bg-gray-100 text-gray-600',
                    };
                @endphp
                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">{{ $ks->statusDok->nama_status ?? '-' }}</span>
            </div>
            @if($waitingUndangan)
            <button @click="showJadwal = true" class="bg-amber-500 text-white px-4 py-1.5 rounded-lg text-xs font-medium hover:bg-amber-600">Ubah Jadwal</button>
            @endif
        </div>
    </div>

    <!-- Waiting Undangan -->
    @if($waitingUndangan)
    <div class="bg-green-50 rounded-lg shadow-sm border border-green-200 mb-4">
        <div class="px-5 py-3 flex items-start gap-2">
            <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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

    <!-- Action Buttons -->
    <div class="flex gap-2 my-10">
        @switch($status)
            @case(1)
                <button @click="showSetujui = true" class="bg-green-500 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-600">Setujui & Jadwalkan</button>
                <button @click="showTolak = true" class="bg-red-500 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-red-600">Tolak</button>
                @break
            @case(2)
                @if(!$hasJadwal)
                <button @click="showJadwal = true" class="bg-amber-500 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-amber-600">Jadwalkan</button>
                @endif
                @break
            @case(3)
                <form method="POST" action="{{ route('admin.kerjasama.lanjutPembahasan', $ks->kerjasama_id) }}" onsubmit="return confirm('Lanjutkan ke penandatanganan?')">
                    @csrf
                    <button class="bg-purple-500 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-purple-600">Lanjut ke Penandatanganan</button>
                </form>
                @break
            @case(4)
                <button @click="showFinalisasi = true" class="bg-green-500 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-600">Finalisasi</button>
                @break
        @endswitch
    </div>

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
            <form method="POST" action="{{ route('admin.kerjasama.setujui', $ks->kerjasama_id) }}">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal & Waktu Pembahasan <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="tanggal_pembahasan" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="flex justify-end gap-2 mt-4">
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
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                        <textarea name="alasan" rows="3" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Tulis alasan..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Perbaikan</label>
                        <textarea name="catatan" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Opsional..."></textarea>
                    </div>
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
            <form method="POST" action="{{ route('admin.kerjasama.jadwalkan', $ks->kerjasama_id) }}">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal & Waktu <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="tanggal_pembahasan" value="{{ $hasJadwal ? $ks->tanggal_pembahasan->format('Y-m-d\TH:i') : '' }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" @click="showJadwal = false" class="border border-gray-300 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Batal</button>
                    <button type="submit" class="bg-amber-500 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-amber-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Finalisasi -->
    <div x-show="showFinalisasi" x-cloak class="fixed inset-0 z-50 flex items-start justify-center bg-black/50 overflow-y-auto py-10" x-transition>
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl p-6" @click.outside="showFinalisasi = false">
            <h3 class="text-lg font-semibold text-green-600 mb-4">Finalisasi Kerja Sama</h3>
            <form method="POST" action="{{ route('admin.kerjasama.finalisasi', $ks->kerjasama_id) }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-3 max-h-[60vh] overflow-y-auto pr-1">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Dokumen Final (TTD)</label>
                        <input type="file" name="dokumen_final" accept=".pdf,.docx,.zip" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Penandatangan Pihak 1</label>
                            <input type="text" name="ttd_pihak1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Penandatangan Pihak 2</label>
                            <input type="text" name="ttd_pihak2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Kode Wilayah</label>
                            <input type="text" name="kode_wilayah" maxlength="20" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jangka (thn)</label>
                            <input type="number" name="jangka_waktu_thn" min="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Pihak 1</label>
                            <input type="text" name="pihak1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Pihak 2</label>
                            <input type="text" name="pihak2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Perihal</label>
                        <textarea name="tentang" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"></textarea>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jumlah K/L</label>
                            <input type="number" name="jumlah_kl_terlibat" min="1" value="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tgl Mulai</label>
                            <input type="date" name="tanggal_mulai_ks" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tgl Berakhir</label>
                            <input type="date" name="tanggal_selesai_ks" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No Pihak 1</label>
                            <input type="text" name="nomor_pihak1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No Pihak 2</label>
                            <input type="text" name="nomor_pihak2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Metode</label>
                            <select name="ks_metode" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                <option value="">-- Pilih --</option>
                                @foreach($metodeList as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Implementasi</label>
                            <select name="ks_implementasi" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                <option value="">-- Pilih --</option>
                                @foreach($implementasiList as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-gray-100">
                    <button type="button" @click="showFinalisasi = false" class="border border-gray-300 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Batal</button>
                    <button type="submit" class="bg-green-500 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-600">Finalisasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
