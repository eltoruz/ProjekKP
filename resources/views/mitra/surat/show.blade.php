@extends('layouts.mitra')

@section('title', 'Detail Surat')
@section('page-title', 'Detail Surat')

@section('page-content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 max-w-xl p-6 space-y-3 text-sm">
    <div><span class="text-gray-500">Jenis:</span> <span class="font-medium">{{ $surat->jenis?->nama_jenis }}</span></div>
    <div><span class="text-gray-500">Instansi:</span> <span class="font-medium">{{ $surat->nama_kl ?? '-' }}</span></div>
    <div><span class="text-gray-500">Nomor Pihak 1:</span> <span class="font-medium">{{ $surat->nomor_pihak1 ?? '-' }}</span></div>
    <div><span class="text-gray-500">Perihal:</span> <span class="font-medium">{{ $surat->tentang ?? '-' }}</span></div>
    <div><span class="text-gray-500">Tanggal:</span> <span class="font-medium">{{ $surat->tanggal_mulai_ks?->format('d M Y') }}</span></div>
    <div><span class="text-gray-500">Pihak 1:</span> <span class="font-medium">{{ $surat->pihak1 ?? '-' }}</span></div>
    <div><span class="text-gray-500">Pihak 2:</span> <span class="font-medium">{{ $surat->pihak2 ?? '-' }}</span></div>
    @if($surat->dokumen_ks)
        <a href="{{ route('mitra.surat.download', $surat->kerjasama_id) }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">Download File</a>
    @endif
    <a href="{{ route('mitra.surat.edit', $surat->kerjasama_id) }}" class="inline-block border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm">Edit Surat</a>
</div>
@endsection
