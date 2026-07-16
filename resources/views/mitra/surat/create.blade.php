@extends('layouts.mitra')

@section('title', 'Tambah Surat')
@section('page-title', 'Tambah Surat Baru')

@section('page-content')
<div class="max-w-xl mx-auto">
    <form action="{{ route('mitra.surat.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Surat <span class="text-red-500">*</span></label>
            <select name="ks_jenis" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Pilih Jenis</option>
                @foreach($jenisList as $j)
                    <option value="{{ $j->id }}">{{ $j->nama_jenis }}</option>
                @endforeach
            </select>
            <p class="text-xs text-gray-400 mt-1">Hanya jenis selain Nota Kesepakatan</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Instansi</label>
            <input type="text" name="nama_kl" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Nama instansi">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat</label>
            <input type="text" name="nomor_surat" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Nomor surat">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Perihal</label>
            <input type="text" name="perihal" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Perihal surat">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat</label>
                <input type="date" name="tanggal_surat" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>
        <div><label class="block text-sm font-medium text-gray-700 mb-1">Pihak 1</label><input type="text" name="pihak1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Pihak pertama"></div>
        <div><label class="block text-sm font-medium text-gray-700 mb-1">Pihak 2</label><input type="text" name="pihak2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Pihak kedua"></div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Upload File</label>
            <input type="file" name="dokumen_ks" accept=".pdf,.docx,.zip" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <p class="text-xs text-gray-400 mt-1">PDF, DOCX, ZIP — Maks 20MB</p>
        </div>
        <div class="flex gap-3 pt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700">Simpan</button>
            <a href="{{ route('mitra.surat.index') }}" class="border border-gray-300 text-gray-700 px-6 py-2.5 rounded-lg text-sm hover:bg-gray-50">Batal</a>
        </div>
    </form>
</div>
@endsection
