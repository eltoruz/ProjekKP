@extends('layouts.mitra')

@section('title', 'Edit Kerja Sama')
@section('page-title', 'Edit Kerja Sama')

@section('page-content')
<div class="max-w-3xl mx-auto">
    <form action="{{ route('mitra.kerjasama.update', $kerjasama->kerjasama_id) }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="border-b border-gray-100 pb-4 mb-4">
            <h3 class="text-base font-semibold text-navy">Informasi Dasar</h3>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kerja Sama <span class="text-red-500">*</span></label>
                <select name="ks_jenis" id="jenisSelect" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                    @foreach($jenisList as $j)
                        <option value="{{ $j->id }}" {{ $kerjasama->ks_jenis == $j->id ? 'selected' : '' }}>{{ $j->nama_jenis }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat Kerja Sama <span class="text-red-500">*</span></label>
                <select name="ks_tingkat" id="tingkatSelect" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                    @foreach($tingkatList as $t)
                        <option value="{{ $t->id }}" {{ $kerjasama->ks_tingkat == $t->id ? 'selected' : '' }}>{{ $t->nama_tingkat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Wilayah</label>
                <input type="text" name="kode_wilayah" value="{{ $kerjasama->kode_wilayah }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                <input type="text" name="provinsi" value="{{ $kerjasama->provinsi }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama K/L / Instansi</label>
                <input type="text" name="nama_kl" value="{{ $kerjasama->nama_kl }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah K/L Terlibat</label>
                <input type="number" name="jumlah_kl_terlibat" value="{{ $kerjasama->jumlah_kl_terlibat }}" min="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>

        <div id="nkFormFields">
            <div class="border-b border-gray-100 pb-4 mb-4">
                <h3 class="text-base font-semibold text-navy">Pihak & Perihal</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pihak Pertama</label>
                    <input type="text" name="pihak1" value="{{ $kerjasama->pihak1 }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pihak Kedua</label>
                    <input type="text" name="pihak2" value="{{ $kerjasama->pihak2 }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tentang</label>
                    <textarea name="tentang" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ $kerjasama->tentang }}</textarea>
                </div>
            </div>

            <div class="border-b border-gray-100 pb-4 mb-4">
                <h3 class="text-base font-semibold text-navy">Kontak</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Narahubung Administrasi</label>
                    <input type="text" name="narahubung_adm" value="{{ $kerjasama->narahubung_adm }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Kontak Administrasi</label>
                    <input type="text" name="nomor_cp_adm" value="{{ $kerjasama->nomor_cp_adm }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Narahubung Teknis</label>
                    <input type="text" name="narahubung_teknis" value="{{ $kerjasama->narahubung_teknis }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Kontak Teknis</label>
                    <input type="text" name="nomor_cp_teknis" value="{{ $kerjasama->nomor_cp_teknis }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            <div class="border-b border-gray-100 pb-4 mb-4">
                <h3 class="text-base font-semibold text-navy">Informasi Tambahan <span class="text-xs text-gray-400 font-normal">(opsional)</span></h3>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Utama Terlibat <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                    <input type="text" name="unit_utama_terlibat" value="{{ $kerjasama->unit_utama_terlibat }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dokumen Pendukung <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                    <input type="text" name="dokumen_pendukung" value="{{ $kerjasama->dokumen_pendukung }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Link/ref dokumen pendukung">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Folder Kerja Sama <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                    <input type="text" name="folder_ks" value="{{ $kerjasama->folder_ks }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Link folder">
                </div>
            </div>
        </div>

        <div id="nkNotAvailable" class="hidden">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-800">
                Fitur untuk jenis kerja sama ini belum tersedia. Saat ini hanya <strong>Nota Kesepakatan</strong> yang dapat diproses.
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" id="submitBtn" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors">
                Simpan Perubahan
            </button>
            <a href="{{ route('mitra.kerjasama.show', $kerjasama->kerjasama_id) }}" class="border border-gray-300 text-gray-700 px-6 py-2.5 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    const jenisSelect = document.getElementById('jenisSelect');
    const nkFields = document.getElementById('nkFormFields');
    const nkNotAvail = document.getElementById('nkNotAvailable');
    const submitBtn = document.getElementById('submitBtn');

    function toggleForm() {
        if (jenisSelect.value === '3') {
            nkFields.classList.remove('hidden');
            nkNotAvail.classList.add('hidden');
            submitBtn.classList.remove('hidden');
        } else if (jenisSelect.value === '') {
            nkFields.classList.add('hidden');
            nkNotAvail.classList.add('hidden');
            submitBtn.classList.remove('hidden');
        } else {
            nkFields.classList.add('hidden');
            nkNotAvail.classList.remove('hidden');
            submitBtn.classList.add('hidden');
        }
    }

    jenisSelect.addEventListener('change', toggleForm);
    toggleForm();
</script>
@endsection
