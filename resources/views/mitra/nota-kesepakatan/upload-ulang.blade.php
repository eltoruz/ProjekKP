@extends('layouts.mitra')

@section('title', 'Upload Ulang Dokumen')
@section('page-title', 'Upload Ulang Dokumen')

@section('page-content')
<div class="max-w-2xl">
    @if($lastReject)
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <h4 class="text-sm font-semibold text-red-700">Catatan Penolakan:</h4>
        <p class="text-sm text-red-600 mt-1">{{ $lastReject['catatan'] ?? $lastReject['alasan'] ?? '-' }}</p>
    </div>
    @endif

    <form action="{{ route('mitra.kerjasama.upload-ulang', $ks->kerjasama_id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
        @csrf
        <p class="text-sm text-gray-600">Silakan perbaiki dokumen sesuai catatan di atas, lalu upload ulang.</p>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Surat Permohonan (Revisi) <span class="text-red-500">*</span></label>
            <input type="file" name="surat_permohonan" accept=".pdf,.docx,.zip" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Draft Nota Kesepakatan (Revisi) <span class="text-red-500">*</span></label>
            <input type="file" name="draft_nk" accept=".pdf,.docx,.zip" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <p class="text-xs text-gray-400">Format: PDF, DOCX — Maks 20MB</p>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700">
                Upload & Ajukan Kembali
            </button>
            <a href="{{ route('mitra.kerjasama.show', $ks->kerjasama_id) }}" class="border border-gray-300 text-gray-700 px-6 py-2.5 rounded-lg text-sm hover:bg-gray-50">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
