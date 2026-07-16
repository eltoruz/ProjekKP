@extends('layouts.mitra')

@section('title', 'Edit Surat')
@section('page-title', 'Edit Surat')

@section('page-content')
<div class="max-w-xl mx-auto">
    <form action="{{ route('mitra.surat.update', $surat->kerjasama_id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Surat <span class="text-red-500">*</span></label>
            <select name="ks_jenis" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Pilih Jenis</option>
                @foreach($jenisList as $j)
                    <option value="{{ $j->id }}" {{ old('ks_jenis', $surat->ks_jenis) == $j->id ? 'selected' : '' }}>{{ $j->nama_jenis }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Instansi</label>
            <input type="text" name="nama_kl" value="{{ old('nama_kl', $surat->nama_kl) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat</label>
            <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $surat->nomor_pihak1) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Perihal</label>
            <input type="text" name="perihal" value="{{ old('perihal', $surat->tentang) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat</label>
                <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', $surat->tanggal_mulai_ks?->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Pihak 1</label>
            <input type="text" name="pihak1" value="{{ old('pihak1', $surat->pihak1) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Pihak 2</label>
            <input type="text" name="pihak2" value="{{ old('pihak2', $surat->pihak2) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Upload File Baru</label>
            <input type="file" name="dokumen_ks" accept=".pdf,.docx,.zip" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <p class="text-xs text-gray-400 mt-1">PDF, DOCX, ZIP - Maks 20MB. Kosongkan jika tidak mengganti file.</p>
        </div>
        <div class="flex gap-3 pt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700">Simpan Perubahan</button>
            <a href="{{ route('mitra.surat.show', $surat->kerjasama_id) }}" class="border border-gray-300 text-gray-700 px-6 py-2.5 rounded-lg text-sm hover:bg-gray-50">Batal</a>
        </div>
    </form>
</div>
@endsection
