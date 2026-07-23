@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('page-content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
    <!-- Total -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-500 uppercase">Total</span>
            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $stats['total'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Kerja Sama</p>
    </div>

    <!-- Perlu Review -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-500 uppercase">Perlu Review</span>
            <div class="w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $stats['perlu_review'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Menunggu Tindakan</p>
    </div>

    <!-- Dalam Pembahasan -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-500 uppercase">Pembahasan</span>
            <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $stats['dalam_pembahasan'] }}</p>
    </div>

    <!-- Dalam Proses -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-500 uppercase">Proses</span>
            <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $stats['dalam_proses'] }}</p>
    </div>

    <!-- Selesai -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-500 uppercase">Selesai</span>
            <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $stats['selesai'] }}</p>
    </div>

    <!-- Berakhir -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-500 uppercase">Berakhir</span>
            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $stats['berakhir'] }}</p>
    </div>
</div>

<!-- Kontainer Pengajuan Terbaru -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-base font-semibold text-slate-800">Pengajuan Terbaru</h3>
            <p class="text-xs text-gray-500 mt-0.5">Daftar pengajuan kerja sama yang baru diajukan atau diperbarui.</p>
        </div>
        <a href="{{ route('admin.kerjasama.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
            Lihat Semua &rarr;
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-y border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mitra / Instansi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tentang</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentSubmissions as $ks)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-gray-500">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $ks->nama_kl ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $ks->jenis?->nama_jenis ?? '-' }}</td>
                    <td class="px-4 py-3 max-w-xs truncate text-gray-600">{{ $ks->tentang ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @php
                            $lastReject = collect($ks->review_log)->filter(fn($l) => ($l['label'] ?? '') === 'Ditolak')->last();
                            $isRejected = $lastReject && !$ks->ks_status_dok;
                            $sColors = [1 => 'bg-blue-100 text-blue-700', 2 => 'bg-yellow-100 text-yellow-700', 3 => 'bg-orange-100 text-orange-700', 4 => 'bg-purple-100 text-purple-700', 5 => 'bg-green-100 text-green-700', 6 => 'bg-gray-100 text-gray-600'];
                            $sLabels = [1 => 'Menunggu Review', 2 => 'Menunggu Jadwal', 3 => 'Pembahasan', 4 => 'Penandatanganan', 5 => 'Selesai', 6 => 'Berakhir'];
                            $colorClass = $isRejected ? 'bg-red-100 text-red-700' : ($sColors[$ks->ks_status_dok] ?? 'bg-gray-100 text-gray-600');
                        @endphp
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                            {{ $isRejected ? 'Ditolak' : ($sLabels[$ks->ks_status_dok] ?? $ks->status_label) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $ks->last_update?->format('d M Y, H:i') }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.kerjasama.review', $ks->kerjasama_id) }}" class="inline-flex items-center gap-1 text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200 px-3 py-1 rounded-lg hover:bg-blue-100 transition-colors">
                            Review &rarr;
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada pengajuan masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
