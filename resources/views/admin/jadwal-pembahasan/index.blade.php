@extends('layouts.admin')

@section('title', 'Jadwal Pembahasan')
@section('page-title', 'Jadwal Pembahasan')

@section('page-content')
@php
    $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $namaHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
@endphp

<div x-data="{ detail: null }" class="space-y-4" @keydown.escape="detail = null">

    <!-- Header Kalender + Navigasi Bulan -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-4 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-base font-bold text-gray-900">{{ $namaBulan[$bulan] }} {{ $tahun }}</h2>
                <p class="text-xs text-gray-500 mt-0.5">
                    Jadwal pembahasan kerja sama yang surat undangannya telah diunggah mitra
                    ({{ $jadwalList->count() }} jadwal bulan ini)
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.jadwal-pembahasan.index', ['bulan' => $bulanSebelumnya->month, 'tahun' => $bulanSebelumnya->year]) }}"
                   aria-label="Bulan sebelumnya"
                   class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-50 hover:text-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <a href="{{ route('admin.jadwal-pembahasan.index') }}"
                   class="px-3.5 py-2 rounded-lg border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Bulan Ini</a>
                <a href="{{ route('admin.jadwal-pembahasan.index', ['bulan' => $bulanBerikutnya->month, 'tahun' => $bulanBerikutnya->year]) }}"
                   aria-label="Bulan berikutnya"
                   class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-50 hover:text-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Nama Hari -->
        <div class="grid grid-cols-7 border-t border-gray-200 bg-slate-50">
            @foreach($namaHari as $hari)
            <div class="px-2 py-2.5 text-center text-[11px] font-bold text-gray-500 uppercase tracking-wide border-r border-gray-200 last:border-r-0">
                <span class="hidden sm:inline">{{ $hari }}</span>
                <span class="sm:hidden">{{ Str::substr($hari, 0, 3) }}</span>
            </div>
            @endforeach
        </div>

        <!-- Grid Tanggal -->
        <div class="border-t border-gray-200">
            @foreach($mingguList as $minggu)
            <div class="grid grid-cols-7 border-b border-gray-200 last:border-b-0">
                @foreach($minggu as $hari)
                <div class="min-h-[7rem] border-r border-gray-200 last:border-r-0 p-1.5 {{ $hari['is_bulan_ini'] ? 'bg-white' : 'bg-slate-50/70' }}">
                    <!-- Nomor Tanggal -->
                    <div class="flex items-center justify-between mb-1">
                        <span class="w-7 h-7 flex items-center justify-center rounded-full text-xs font-bold
                            {{ $hari['is_hari_ini']
                                ? 'bg-indigo-600 text-white shadow-2xs'
                                : ($hari['is_bulan_ini'] ? 'text-gray-700' : 'text-gray-400') }}">
                            {{ $hari['tanggal']->day }}
                        </span>
                        @if($hari['events']->count() > 1)
                        <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 px-1.5 py-0.5 rounded">{{ $hari['events']->count() }}</span>
                        @endif
                    </div>

                    <!-- Event Kerja Sama -->
                    <div class="space-y-1">
                        @foreach($hari['events'] as $ks)
                        <button type="button"
                                @click="detail = {
                                    nama: {{ Js::from($ks->nama_kl ?? '-') }},
                                    jenis: {{ Js::from($ks->jenis?->nama_jenis ?? '-') }},
                                    tingkat: {{ Js::from($ks->tingkat?->nama_tingkat ?? '-') }},
                                    status: {{ Js::from($ks->status_label) }},
                                    tentang: {{ Js::from($ks->tentang ?: '-') }},
                                    waktu: {{ Js::from($ks->tanggal_pembahasan->format('d M Y, H:i')) }},
                                    url: {{ Js::from(route('admin.kerjasama.review', $ks->kerjasama_id)) }}
                                }"
                                class="w-full text-left px-1.5 py-1 rounded-md bg-orange-50 border border-orange-200 text-orange-900 hover:bg-orange-100 hover:border-orange-300 transition-colors focus:outline-none focus:ring-2 focus:ring-orange-400/40 cursor-pointer">
                            <span class="block text-[10px] font-bold text-orange-700 leading-tight">{{ $ks->tanggal_pembahasan->format('H:i') }}</span>
                            <span class="block text-[11px] font-semibold leading-tight truncate">{{ $ks->nama_kl ?? '-' }}</span>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>

    <!-- Keterangan -->
    <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 px-1">
        <span class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded bg-orange-50 border border-orange-200"></span>
            Dokumen dalam proses pembahasan
        </span>
        <span class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-full bg-indigo-600"></span>
            Hari ini
        </span>
    </div>

    @if($jadwalList->isEmpty())
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-5 py-8 text-center">
        <p class="text-sm font-medium text-gray-700 mb-1">Belum ada jadwal pembahasan pada {{ $namaBulan[$bulan] }} {{ $tahun }}.</p>
        <p class="text-xs text-gray-500">Jadwal muncul otomatis setelah mitra mengunggah surat undangan pembahasan.</p>
    </div>
    @endif

    <!-- MODAL: Detail Ringkas Kerja Sama -->
    <div x-show="detail" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" x-transition>
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md" @click.outside="detail = null">
            <div class="px-5 py-4 border-b border-gray-100 flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-base font-bold text-gray-900" x-text="detail?.nama"></h3>
                    <p class="text-xs text-gray-500 mt-0.5" x-text="detail?.waktu"></p>
                </div>
                <button type="button" @click="detail = null" aria-label="Tutup"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-colors shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="px-5 py-4 space-y-3 text-sm">
                <div>
                    <span class="text-xs text-gray-400">Jenis Kerja Sama</span>
                    <p class="text-sm" x-text="detail?.jenis"></p>
                </div>
                <div>
                    <span class="text-xs text-gray-400">Tingkat</span>
                    <p class="text-sm" x-text="detail?.tingkat"></p>
                </div>
                <div>
                    <span class="text-xs text-gray-400">Status Dokumen</span>
                    <p>
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold bg-orange-50 text-orange-800 border border-orange-200">
                            <span class="w-2 h-2 rounded-full bg-current shrink-0"></span>
                            <span x-text="detail?.status"></span>
                        </span>
                    </p>
                </div>
                <div>
                    <span class="text-xs text-gray-400">Tentang</span>
                    <p class="text-sm text-gray-600" x-text="detail?.tentang"></p>
                </div>
            </div>

            <div class="px-5 py-4 border-t border-gray-100 flex justify-end gap-2">
                <button type="button" @click="detail = null"
                        class="border border-gray-300 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Tutup</button>
                <a :href="detail?.url"
                   class="inline-flex items-center gap-1.5 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                    Lihat Detail Kerja Sama
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
