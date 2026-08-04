@extends('layouts.admin')

@section('title', 'Tambah Kerja Sama')
@section('page-title', 'Tambah Kerja Sama')

@section('page-content')
<div class="max-w-5xl">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('admin.kerjasama.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-slate-800">Tambah Data Baru</h2>
            </div>
            <div class="p-6 space-y-5">
                <!-- Baris 1: Jenis, Tingkat, Kode Wilayah, No Input -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Jenis <span class="text-red-500">*</span></label>
                        <select name="ks_jenis" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">Pilih</option>
                            @foreach($jenisList as $id => $name)
                            @if($id != 3)
                            <option value="{{ $id }}" {{ old('ks_jenis') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Tingkat <span class="text-red-500">*</span></label>
                        <select name="ks_tingkat" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">Pilih</option>
                            @foreach($tingkatList as $id => $name)
                            <option value="{{ $id }}" {{ old('ks_tingkat') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Kode Wilayah</label>
                        <input type="text" name="kode_wilayah" value="{{ old('kode_wilayah') }}" maxlength="20" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">No. Input</label>
                        <input type="text" name="no_input" value="{{ old('no_input') }}" maxlength="50" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>

                <!-- Baris 2: Instansi, Jml K/L -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Nama Kementerian / Lembaga / Instansi <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_kl" value="{{ old('nama_kl') }}" required maxlength="200" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Jumlah K/L</label>
                        <input type="number" name="jumlah_kl_terlibat" value="{{ old('jumlah_kl_terlibat', 1) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>

                <!-- Baris 3: Pihak 1, Pihak 2 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Pihak 1</label>
                        <input type="text" name="pihak1" value="{{ old('pihak1') }}" maxlength="200" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Pihak 2</label>
                        <input type="text" name="pihak2" value="{{ old('pihak2') }}" maxlength="200" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>

                <!-- Baris 4: No Pihak 1, No Pihak 2, TTD 1, TTD 2 -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">No. Pihak 1</label>
                        <input type="text" name="nomor_pihak1" value="{{ old('nomor_pihak1') }}" maxlength="100" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">No. Pihak 2</label>
                        <input type="text" name="nomor_pihak2" value="{{ old('nomor_pihak2') }}" maxlength="100" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">TTD Pihak 1</label>
                        <input type="text" name="ttd_pihak1" value="{{ old('ttd_pihak1') }}" maxlength="200" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">TTD Pihak 2</label>
                        <input type="text" name="ttd_pihak2" value="{{ old('ttd_pihak2') }}" maxlength="200" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>

                <!-- Baris 5: Perihal -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Perihal</label>
                    <textarea name="tentang" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('tentang') }}</textarea>
                </div>

                <!-- Baris 6: Jangka, Tgl Mulai, Tgl Berakhir -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Jangka (thn)</label>
                        <input type="number" name="jangka_waktu_thn" value="{{ old('jangka_waktu_thn') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Tgl Mulai</label>
                        <x-date-picker name="tanggal_mulai_ks" :value="old('tanggal_mulai_ks')" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Tgl Berakhir</label>
                        <x-date-picker name="tanggal_selesai_ks" :value="old('tanggal_selesai_ks')" />
                    </div>
                </div>

                <!-- Baris 7: Kontak -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Narahubung Adm</label>
                        <input type="text" name="narahubung_adm" value="{{ old('narahubung_adm') }}" maxlength="200" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">No. Kontak Adm</label>
                        <input type="text" name="nomor_cp_adm" value="{{ old('nomor_cp_adm') }}" maxlength="50" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Narahubung Teknis</label>
                        <input type="text" name="narahubung_teknis" value="{{ old('narahubung_teknis') }}" maxlength="200" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">No. Kontak Teknis</label>
                        <input type="text" name="nomor_cp_teknis" value="{{ old('nomor_cp_teknis') }}" maxlength="50" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>

                <!-- Baris 8: Status, Metode, Implementasi -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Status Dok</label>
                        <select name="ks_status_dok" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">-- Pilih --</option>
                            @foreach($statusList as $id => $name)
                            <option value="{{ $id }}" {{ old('ks_status_dok') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Metode</label>
                        <select name="ks_metode" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">-- Pilih --</option>
                            @foreach($metodeList as $id => $name)
                            <option value="{{ $id }}" {{ old('ks_metode') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Implementasi</label>
                        <select name="ks_implementasi" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">-- Pilih --</option>
                            @foreach($implementasiList as $id => $name)
                            <option value="{{ $id }}" {{ old('ks_implementasi') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Baris 9: Unit Utama, Dokumen -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Unit Utama Terlibat</label>
                        <input type="text" name="unit_utama_terlibat" value="{{ old('unit_utama_terlibat') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Dokumen Final</label>
                        <input type="file" name="dokumen_ks" accept=".pdf" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Dokumen Pendukung</label>
                        <input type="file" name="dokumen_pendukung" accept=".pdf" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-xs">
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-2">
                <a href="{{ route('admin.kerjasama.index') }}" class="border border-gray-300 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Batal</a>
                <button type="submit" class="bg-indigo-500 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-indigo-600">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
