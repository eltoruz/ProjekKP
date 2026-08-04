@extends('layouts.mitra')

@section('title', 'Tambah Kerja Sama')
@section('page-title', 'Tambah Kerja Sama Baru')

@section('page-content')
<div class="max-w-3xl mx-auto">
    <form action="{{ route('mitra.kerjasama.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6" id="createForm">
        @csrf

        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-700">
            <ul class="list-disc pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div>
            <h3 class="text-base font-semibold text-navy mb-4">Informasi Dasar</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kerja Sama <span class="text-red-500">*</span></label>
                    <select name="ks_jenis" id="jenisSelect" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">Pilih Jenis</option>
                        @foreach($jenisList as $j)
                            <option value="{{ $j->id }}" {{ old('ks_jenis') == $j->id ? 'selected' : '' }}>{{ $j->nama_jenis }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat <span class="text-red-500">*</span></label>
                    <select id="tingkatDisplay" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm disabled:bg-gray-100 disabled:text-gray-500">
                        <option value="">Pilih Tingkat</option>
                        @foreach($tingkatList as $t)
                            <option value="{{ $t->id }}" {{ old('ks_tingkat') == $t->id ? 'selected' : '' }}>{{ $t->nama_tingkat }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="ks_tingkat" id="tingkatHidden" value="{{ old('ks_tingkat') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kementerian / Lembaga / Instansi <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_kl" value="{{ old('nama_kl') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Nama instansi/lembaga mitra">
                </div>
            </div>
        </div>

        {{-- Non-NK fields --}}
        <div id="nonNkFields" class="hidden">
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
                Fitur untuk jenis kerja sama ini belum tersedia. Saat ini hanya <strong>Nota Kesepakatan</strong> yang dapat diproses.
            </div>
        </div>

        {{-- NK-only fields --}}
        <div id="nkFields" class="hidden">
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-base font-semibold text-navy mb-1">Upload Dokumen Nota Kesepakatan</h3>
                <p class="text-xs text-gray-500 mb-4">Upload dua dokumen yang diperlukan: Surat Permohonan dan Draft Nota Kesepakatan.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">1. Surat Permohonan <span class="text-red-500">*</span></label>
                        <input type="file" name="surat_permohonan" accept=".pdf" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <p class="text-xs text-gray-400 mt-1">Surat dari Kepala Daerah ke Sekjen Kemendikdasmen</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">2. Draft Nota Kesepakatan <span class="text-red-500">*</span></label>
                        <input type="file" name="draft_nk" accept=".pdf" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <p class="text-xs text-gray-400 mt-1">Draft NK yang akan dibahas bersama</p>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-3">Format: PDF — Maks 20MB per file</p>
            </div>

            <div>
                <h3 class="text-base font-semibold text-navy mb-4 mt-6">Kontak</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Narahubung Administrasi <span class="text-red-500">*</span></label>
                        <input type="text" name="narahubung_adm" value="{{ old('narahubung_adm') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Kontak Administrasi <span class="text-red-500">*</span></label>
                        <input type="text" name="nomor_cp_adm" value="{{ old('nomor_cp_adm') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Narahubung Teknis <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                        <input type="text" name="narahubung_teknis" value="{{ old('narahubung_teknis') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Kontak Teknis <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                        <input type="text" name="nomor_cp_teknis" value="{{ old('nomor_cp_teknis') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>
            </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" id="submitBtn" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-600">
                Simpan Kerja Sama
            </button>
            <a href="{{ route('mitra.kerjasama.index') }}" class="border border-gray-300 text-gray-700 px-6 py-2.5 rounded-lg text-sm hover:bg-gray-50">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    const jenisSelect = document.getElementById('jenisSelect');
    const tingkatDisplay = document.getElementById('tingkatDisplay');
    const tingkatHidden = document.getElementById('tingkatHidden');
    const nkFields = document.getElementById('nkFields');
    const nonNkFields = document.getElementById('nonNkFields');
    const submitBtn = document.getElementById('submitBtn');

    function disableAll(container) {
        if (!container) return;
        container.querySelectorAll('input, textarea, select').forEach(function(el) {
            el.setAttribute('disabled', 'disabled');
        });
    }
    function enableAll(container) {
        if (!container) return;
        container.querySelectorAll('input, textarea, select').forEach(function(el) {
            el.removeAttribute('disabled');
        });
    }

    function toggleForm() {
        var val = jenisSelect.value;
        if (val === '3') {
            tingkatDisplay.value = '3';
            tingkatDisplay.setAttribute('disabled', 'disabled');
            tingkatHidden.value = '3';
            nkFields.classList.remove('hidden');
            nonNkFields.classList.add('hidden');
            submitBtn.classList.remove('hidden');
            enableAll(nkFields);
            disableAll(nonNkFields);
        } else if (val === '') {
            tingkatDisplay.value = '';
            tingkatDisplay.removeAttribute('disabled');
            tingkatHidden.value = '';
            nkFields.classList.add('hidden');
            nonNkFields.classList.add('hidden');
            submitBtn.classList.remove('hidden');
            disableAll(nkFields);
            disableAll(nonNkFields);
        } else {
            tingkatDisplay.value = '';
            tingkatDisplay.removeAttribute('disabled');
            tingkatHidden.value = '';
            nkFields.classList.add('hidden');
            nonNkFields.classList.remove('hidden');
            submitBtn.classList.add('hidden');
            disableAll(nkFields);
            enableAll(nonNkFields);
        }
    }

    jenisSelect.addEventListener('change', toggleForm);
    tingkatDisplay.addEventListener('change', function() {
        tingkatHidden.value = this.value;
    });
    toggleForm();
</script>
@endsection
