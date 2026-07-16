@extends('layouts.mitra')

@section('title', 'Daftar Surat')
@section('page-title', 'Daftar Surat')

@section('page-content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <form method="GET" class="flex gap-3 items-center">
            <select name="jenis" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Jenis Surat</option>
                @foreach($jenisList as $j)
                    <option value="{{ $j->id }}" {{ request('jenis') == $j->id ? 'selected' : '' }}>{{ $j->nama_jenis }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filter</button>
        </form>
        <a href="{{ route('mitra.surat.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">+ Tambah Surat</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Surat</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instansi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perihal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($surat as $s)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $loop->iteration + $surat->firstItem() - 1 }}</td>
                    <td class="px-4 py-3">{{ $s->jenis?->nama_jenis }}</td>
                    <td class="px-4 py-3 max-w-xs truncate">{{ $s->nama_kl ?? '-' }}</td>
                    <td class="px-4 py-3 max-w-xs truncate">{{ $s->tentang ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $s->tanggal_mulai_ks?->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('mitra.surat.show', $s->kerjasama_id) }}" class="text-blue-600 text-xs">Detail</a>
                        <a href="{{ route('mitra.surat.edit', $s->kerjasama_id) }}" class="text-yellow-600 text-xs ml-2">Edit</a>
                        @if($s->dokumen_ks)
                        <a href="{{ route('mitra.surat.download', $s->kerjasama_id) }}" class="text-blue-600 text-xs ml-2">Download</a>
                        @endif
                        <form action="{{ route('mitra.surat.destroy', $s->kerjasama_id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 text-xs ml-2" onclick="return confirm('Hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada surat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">{{ $surat->links() }}</div>
</div>
@endsection
