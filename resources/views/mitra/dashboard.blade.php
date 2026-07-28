@extends('layouts.mitra')

@section('title', 'Dashboard Mitra')
@section('page-title', 'Dashboard')

@section('page-content')
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
    <x-stat-card title="Total Pengajuan" :value="$total" bgColor="bg-blue-100"
        icon='<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' />

    <x-stat-card title="Draft" :value="$belum" bgColor="bg-gray-100"
        icon='<svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>' />

    <x-stat-card title="Dalam Pembahasan" :value="$dibahas" bgColor="bg-blue-200"
        icon='<svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>' />

    <x-stat-card title="Selesai" :value="$selesai" bgColor="bg-green-100"
        icon='<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' />

    <x-stat-card title="Tahap Akhir" :value="$disetujui" bgColor="bg-yellow-100"
        icon='<svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' />

    <x-stat-card title="Masa Berlaku Berakhir" :value="$expired" bgColor="bg-gray-200"
        icon='<svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' />
</div>

@if(isset($earlyWarningList) && count($earlyWarningList) > 0)
<div class="bg-gradient-to-r from-amber-500 to-orange-600 rounded-2xl shadow-md p-6 mb-6 text-white">
    <div class="flex items-center justify-between border-b border-white/20 pb-3 mb-3">
        <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 animate-pulse text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            <h3 class="text-sm font-bold">Peringatan Masa Berlaku Kerja Sama (H-90 / H-60 / H-30)</h3>
        </div>
        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-white/20 text-white">
            {{ count($earlyWarningList) }} Berkas Perlu Perhatian
        </span>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($earlyWarningList as $item)
        <div class="bg-white/10 backdrop-blur-md rounded-xl p-3 border border-white/20 flex flex-col justify-between text-xs">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="font-bold truncate">{{ $item['kerjasama']->nama_kl }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold shadow-2xs {{ $item['level'] === 'danger' ? 'bg-red-500 text-white' : 'bg-amber-300 text-amber-950' }}">
                        {{ $item['badge_text'] }}
                    </span>
                </div>
                <p class="text-[11px] text-white/90 line-clamp-1">{{ $item['kerjasama']->tentang }}</p>
            </div>
            <div class="mt-2 pt-2 border-t border-white/15 flex items-center justify-between text-[11px]">
                <span class="text-white/80">Berakhir: <strong>{{ $item['tanggal_selesai'] }}</strong></span>
                <a href="{{ route('mitra.kerjasama.show', $item['kerjasama']->kerjasama_id) }}" class="underline font-semibold hover:text-white">Detail &rarr;</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

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
