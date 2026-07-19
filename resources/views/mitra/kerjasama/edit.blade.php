@extends('layouts.mitra')

@section('title', 'Edit Kerja Sama')
@section('page-title', 'Edit Kerja Sama')

@section('page-content')
<div class="max-w-3xl mx-auto">
    <form action="{{ route('mitra.kerjasama.update', $kerjasama->kerjasama_id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="border-b border-gray-100 pb-4 mb-4">
            <h3 class="text-base font-semibold text-navy">Informasi Dasar</h3>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kerja Sama <span class="text-red-500">*</span></label>
                <select name="ks_jenis" id="jenisSelect" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @foreach($jenisList as $j)
                        <option value="{{ $j->id }}" {{ $kerjasama->ks_jenis == $j->id ? 'selected' : '' }}>{{ $j->nama_jenis }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat <span class="text-red-500">*</span></label>
                <select name="ks_tingkat" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @foreach($tingkatList as $t)
                        <option value="{{ $t->id }}" {{ $kerjasama->ks_tingkat == $t->id ? 'selected' : '' }}>{{ $t->nama_tingkat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama K/L / Instansi <span class="text-red-500">*</span></label>
                <input type="text" name="nama_kl" value="{{ $kerjasama->nama_kl }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>

        <div id="nkFormFields">
            <div class="border-b border-gray-100 pb-4 mb-4">
                <h3 class="text-base font-semibold text-navy">Dokumen Nota Kesepakatan</h3>
            </div>

            @php $existingFiles = $kerjasama->dokumen_ks ? (json_decode($kerjasama->dokumen_ks, true) ?: []) : []; @endphp
            @if(count($existingFiles))
            <div class="bg-gray-50 rounded-lg p-3 text-sm">
                <p class="text-gray-500 mb-2">Dokumen saat ini:</p>
                <ul class="space-y-1">
                    @foreach($existingFiles as $idx => $f)
                    <li class="text-gray-700">📄 {{ basename($f) }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-xs text-gray-500 mb-3">Upload ulang untuk mengganti dokumen. Kosongkan jika tidak ingin mengganti.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">1. Surat Permohonan</label>
                        <input type="file" name="surat_permohonan" accept=".pdf,.docx,.zip" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">2. Draft Nota Kesepakatan</label>
                        <input type="file" name="draft_nk" accept=".pdf,.docx,.zip" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-3">Format: PDF, DOCX, ZIP — Maks 20MB per file</p>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Narahubung Teknis <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                    <input type="text" name="narahubung_teknis" value="{{ $kerjasama->narahubung_teknis }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Kontak Teknis <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                    <input type="text" name="nomor_cp_teknis" value="{{ $kerjasama->nomor_cp_teknis }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
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
