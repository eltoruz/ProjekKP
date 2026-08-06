@extends('layouts.admin')

@section('title', 'Edit Kerja Sama')
@section('page-title', 'Edit Kerja Sama')

@section('page-content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('admin.kerjasama.update', $ks->kerjasama_id) }}">
            @csrf @method('PUT')
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-slate-800">Form Edit Data</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Jenis <span class="text-red-500">*</span></label>
                        <select name="ks_jenis" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            @foreach($jenisList as $id => $name)
                            <option value="{{ $id }}" {{ $ks->ks_jenis == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Tingkat <span class="text-red-500">*</span></label>
                        <select name="ks_tingkat" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            @foreach($tingkatList as $id => $name)
                            <option value="{{ $id }}" {{ $ks->ks_tingkat == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Kode Wilayah</label>
                        <input type="text" name="kode_wilayah" value="{{ $ks->kode_wilayah }}" maxlength="20" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Nama Kementerian / Lembaga / Instansi</label>
                        <input type="text" name="nama_kl" value="{{ $ks->nama_kl }}" maxlength="200" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Jumlah K/L</label>
                        <input type="number" name="jumlah_kl_terlibat" value="{{ $ks->jumlah_kl_terlibat }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Pihak 1</label>
                        <input type="text" name="pihak1" value="{{ $ks->pihak1 }}" maxlength="200" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Pihak 2</label>
                        <input type="text" name="pihak2" value="{{ $ks->pihak2 }}" maxlength="200" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Perihal</label>
                    <textarea name="tentang" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ $ks->tentang }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Jangka (thn)</label>
                        <input type="number" name="jangka_waktu_thn" value="{{ $ks->jangka_waktu_thn }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Tgl Mulai</label>
                        <x-date-picker name="tanggal_mulai_ks" :value="$ks->tanggal_mulai_ks?->format('Y-m-d')" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Tgl Berakhir</label>
                        <x-date-picker name="tanggal_selesai_ks" :value="$ks->tanggal_selesai_ks?->format('Y-m-d')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Narahubung Adm</label>
                        <input type="text" name="narahubung_adm" value="{{ $ks->narahubung_adm }}" maxlength="200" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">No. Kontak Adm</label>
                        <input type="text" name="nomor_cp_adm" value="{{ $ks->nomor_cp_adm }}" maxlength="50" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Narahubung Teknis</label>
                        <input type="text" name="narahubung_teknis" value="{{ $ks->narahubung_teknis }}" maxlength="200" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">No. Kontak Teknis</label>
                        <input type="text" name="nomor_cp_teknis" value="{{ $ks->nomor_cp_teknis }}" maxlength="50" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Status Dok</label>
                        <select name="ks_status_dok" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">-- Pilih --</option>
                            @foreach($statusList as $id => $name)
                            <option value="{{ $id }}" {{ $ks->ks_status_dok == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Metode</label>
                        <select name="ks_metode" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">-- Pilih --</option>
                            @foreach($metodeList as $id => $name)
                            <option value="{{ $id }}" {{ $ks->ks_metode == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Implementasi</label>
                        <select name="ks_implementasi" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">-- Pilih --</option>
                            @foreach($implementasiList as $id => $name)
                            <option value="{{ $id }}" {{ $ks->ks_implementasi == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Unit Utama Terlibat</label>
                    <input type="text" name="unit_utama_terlibat" value="{{ $ks->unit_utama_terlibat }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
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
