@extends('layouts.mitra')

@section('title', 'Pelaporan Berkala')
@section('page-title', 'Pelaporan Berkala')

@section('page-content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap gap-3 justify-between items-center">
        <div>
            <h2 class="text-base font-bold text-gray-900">Daftar Kerja Sama yang Dapat Dilaporkan</h2>
            <p class="text-xs text-gray-500 mt-0.5">Kerja sama berstatus Selesai. Unggah laporan penggunaan data 2x/tahun (Semester 1 &amp; Semester 2).</p>
        </div>
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama mitra atau tentang..."
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:ring-blue-500 focus:border-blue-500">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Cari</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mitra</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Pelaporan</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Jumlah Laporan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Laporan Terakhir</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($items as $ks)
                @php
                    $reportsCount = $ks->reports->count();
                    $latestReport = $ks->reports->first();
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $loop->iteration + $items->firstItem() - 1 }}</td>
                    <td class="px-4 py-3 font-medium">{{ $ks->nama_kl ?? 'N/A' }}</td>
                    <td class="px-4 py-3">{{ $ks->jenis?->nama_jenis ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($ks->is_reporting_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Belum Aktif
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100 text-xs font-semibold">{{ $reportsCount }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        @if($latestReport)
                            {{ $latestReport->periode }} {{ $latestReport->tahun }}
                            <span class="block text-[11px] text-gray-400">{{ $latestReport->created_at?->format('d M Y') }}</span>
                        @else
                            <span class="text-gray-400">Belum ada</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('mitra.kerjasama.laporan', $ks->kerjasama_id) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-2xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Kelola Laporan
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada kerja sama berstatus Selesai yang dapat dilaporkan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-100">
        {{ $items->links() }}
    </div>
</div>
@endsection
