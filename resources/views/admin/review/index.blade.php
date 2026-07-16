@extends('layouts.app')

@section('title', 'Review Pengajuan - Admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <header class="bg-navy px-6 py-4 flex justify-between items-center shadow-md">
        <div class="flex items-center gap-3">
            <img src="https://pelayanan.data.kemendikdasmen.go.id/assets/imge/tutwuri.png" class="h-8" alt="">
            <div>
                <h1 class="text-lg font-semibold text-white">Review Pengajuan</h1>
                <p class="text-xs text-white/50">Admin Pusdatin</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-xs text-white bg-primary/80 px-3 py-1.5 rounded-full font-medium">Admin</span>
            <a href="/" class="text-white/70 text-sm hover:text-red-300">Keluar</a>
        </div>
    </header>

    <div class="px-6 py-6 max-w-6xl mx-auto">
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                <span class="text-gray-500 text-sm">Menunggu Review</span>
                <p class="text-2xl font-bold text-primary">{{ $needReview }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mitra</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($kerjasamas as $ks)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-500">{{ $loop->iteration + $kerjasamas->firstItem() - 1 }}</td>
                        <td class="px-4 py-3 font-medium">{{ $ks->nama_kl ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $ks->jenis?->nama_jenis }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$ks->status_pengajuan" :label="$ks->status_label" /></td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.review.show', $ks->kerjasama_id) }}" class="text-primary text-xs font-medium hover:underline">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Tidak ada pengajuan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $kerjasamas->links() }}</div>
    </div>
</div>
@endsection
