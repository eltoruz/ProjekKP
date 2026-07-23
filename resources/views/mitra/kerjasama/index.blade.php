@extends('layouts.mitra')

@section('title', 'Daftar Kerja Sama')
@section('page-title', 'Daftar Kerja Sama')

@section('page-content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap gap-3 justify-between items-center">
        <form method="GET" class="flex flex-wrap gap-3 items-center flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, pihak, tentang..."
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:ring-blue-500 focus:border-blue-500">
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                @foreach(\App\Models\KsStatusDok::all() as $s)
                    <option value="{{ $s->id }}" {{ request('status') == $s->id ? 'selected' : '' }}>{{ $s->nama_status }}</option>
                @endforeach
            </select>
            <select name="jenis" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Jenis</option>
                @foreach($jenisList as $j)
                    <option value="{{ $j->id }}" {{ request('jenis') == $j->id ? 'selected' : '' }}>{{ $j->nama_jenis }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filter</button>
        </form>
        <a href="{{ route('mitra.kerjasama.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">+ Tambah Baru</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mitra</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tentang</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Informasi Pengajuan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($kerjasamas as $ks)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $loop->iteration + $kerjasamas->firstItem() - 1 }}</td>
                    <td class="px-4 py-3 font-medium">{{ $ks->nama_kl ?? 'N/A' }}</td>
                    <td class="px-4 py-3">{{ $ks->jenis?->nama_jenis ?? '-' }}</td>
                    <td class="px-4 py-3 max-w-xs truncate">{{ $ks->tentang ?? '-' }}</td>
                    <td class="px-4 py-3">
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
                    <td class="px-4 py-3 text-gray-500">{{ $ks->last_update?->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('mitra.kerjasama.show', $ks->kerjasama_id) }}" class="text-blue-600 hover:text-blue-800 text-sm">Detail</a>
                        @if($ks->canBeDeletedByMitra())
                        <form action="{{ route('mitra.kerjasama.destroy', $ks->kerjasama_id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada data kerja sama.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-100">
        {{ $kerjasamas->links() }}
    </div>
</div>
@endsection
