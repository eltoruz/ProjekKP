@extends('layouts.mitra')

@section('title', 'Dashboard Mitra')
@section('page-title', 'Dashboard')

@section('page-content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <!-- Total -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-500 uppercase">Total Pengajuan</span>
            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-navy">{{ $total }}</p>
    </div>

    <!-- Draft -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-500 uppercase">Draft</span>
            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-navy">{{ $belum }}</p>
    </div>

    <!-- Dalam Pembahasan -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-500 uppercase">Pembahasan</span>
            <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-navy">{{ $dibahas }}</p>
    </div>

    <!-- Selesai -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-500 uppercase">Selesai</span>
            <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-navy">{{ $selesai }}</p>
    </div>

    <!-- Tahap Akhir -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-500 uppercase">Tahap Akhir</span>
            <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-navy">{{ $disetujui }}</p>
    </div>

    <!-- Berakhir -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-500 uppercase">Berakhir</span>
            <div class="w-8 h-8 rounded-lg bg-gray-200 flex items-center justify-center">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-navy">{{ $expired }}</p>
    </div>
</div>

@if($upcoming->count())
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-base font-semibold text-navy mb-3">Jadwal Pembahasan Mendatang</h3>
    <div class="space-y-3">
        @foreach($upcoming as $ks)
        <div class="py-3 border-b border-gray-100 last:border-0">
            <div class="flex items-center justify-between mb-1">
                <p class="text-sm font-medium text-navy">{{ $ks->nama_kl ?? 'N/A' }}</p>
                <p class="text-sm font-semibold text-primary">{{ $ks->tanggal_pembahasan?->format('d M Y, H:i') }}</p>
            </div>
            <p class="text-xs text-gray-500 mb-1">{{ $ks->tentang }}</p>
        </div>
        @endforeach
    </div>
</div>
@endif

@if($recent->count())
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-base font-semibold text-navy">Pengajuan Terbaru</h3>
        <a href="{{ route('mitra.kerjasama.index') }}" class="text-xs text-primary hover:underline">Lihat Semua</a>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Mitra</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Informasi Pengajuan</th>
                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($recent as $ks)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2.5">{{ $ks->nama_kl ?? '-' }}</td>
                <td class="px-4 py-2.5 text-gray-500">{{ $ks->jenis?->nama_jenis }}</td>
                <td class="px-4 py-2.5">
                    @if($ks->ks_status_dok == 2)
                        @if(!$ks->tanggal_pembahasan)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Menunggu Jadwal</span>
                        @elseif(!$ks->hasSuratUndangan())
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Upload Undangan</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700">Menunggu Pembahasan</span>
                        @endif
                    @elseif($ks->ks_status_dok)
                        @php
                            $sColors = [1 => 'bg-blue-100 text-blue-700', 3 => 'bg-orange-100 text-orange-700', 4 => 'bg-purple-100 text-purple-700', 5 => 'bg-green-100 text-green-700', 6 => 'bg-gray-100 text-gray-600'];
                            $sLabels = [1 => 'Menunggu Review', 3 => 'Pembahasan', 4 => 'Penandatanganan', 5 => 'Selesai', 6 => 'Berakhir'];
                        @endphp
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sColors[$ks->ks_status_dok] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $sLabels[$ks->ks_status_dok] ?? $ks->status_label }}
                        </span>
                    @elseif($ks->status_label === 'Ditolak')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Ditolak</span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Draft</span>
                    @endif
                </td>
                <td class="px-4 py-2.5 text-right">
                    <a href="{{ route('mitra.kerjasama.show', $ks->kerjasama_id) }}" class="text-primary text-xs hover:underline">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection
