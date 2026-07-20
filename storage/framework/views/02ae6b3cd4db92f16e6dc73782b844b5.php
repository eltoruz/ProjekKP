<?php $__env->startSection('title', 'Tambah Kerja Sama'); ?>
<?php $__env->startSection('page-title', 'Tambah Kerja Sama Baru'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="max-w-3xl mx-auto">
    <form action="<?php echo e(route('mitra.kerjasama.store')); ?>" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6" id="createForm">
        <?php echo csrf_field(); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-700">
            <ul class="list-disc pl-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </ul>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div>
            <h3 class="text-base font-semibold text-navy mb-4">Informasi Dasar</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kerja Sama <span class="text-red-500">*</span></label>
                    <select name="ks_jenis" id="jenisSelect" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">Pilih Jenis</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $jenisList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($j->id); ?>" <?php echo e(old('ks_jenis') == $j->id ? 'selected' : ''); ?>><?php echo e($j->nama_jenis); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat <span class="text-red-500">*</span></label>
                    <select id="tingkatDisplay" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm disabled:bg-gray-100 disabled:text-gray-500">
                        <option value="">Pilih Tingkat</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tingkatList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($t->id); ?>" <?php echo e(old('ks_tingkat') == $t->id ? 'selected' : ''); ?>><?php echo e($t->nama_tingkat); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                    <input type="hidden" name="ks_tingkat" id="tingkatHidden" value="<?php echo e(old('ks_tingkat')); ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kementerian / Lembaga / Instansi <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_kl" value="<?php echo e(old('nama_kl')); ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Nama instansi/lembaga mitra">
                </div>
            </div>
        </div>

        
        <div id="nonNkFields" class="hidden">
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
                Fitur untuk jenis kerja sama ini belum tersedia. Saat ini hanya <strong>Nota Kesepakatan</strong> yang dapat diproses.
            </div>
        </div>

        
        <div id="nkFields" class="hidden">
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-base font-semibold text-navy mb-1">Upload Dokumen Nota Kesepakatan</h3>
                <p class="text-xs text-gray-500 mb-4">Upload dua dokumen yang diperlukan: Surat Permohonan dan Draft Nota Kesepakatan.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">1. Surat Permohonan <span class="text-red-500">*</span></label>
                        <input type="file" name="surat_permohonan" accept=".pdf,.docx,.zip" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <p class="text-xs text-gray-400 mt-1">Surat dari Kepala Daerah ke Sekjen Kemendikdasmen</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">2. Draft Nota Kesepakatan <span class="text-red-500">*</span></label>
                        <input type="file" name="draft_nk" accept=".pdf,.docx,.zip" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <p class="text-xs text-gray-400 mt-1">Draft NK yang akan dibahas bersama</p>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-3">Format: PDF, DOCX, ZIP — Maks 20MB per file</p>
            </div>

            <div>
                <h3 class="text-base font-semibold text-navy mb-4 mt-6">Kontak</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Narahubung Administrasi <span class="text-red-500">*</span></label>
                        <input type="text" name="narahubung_adm" value="<?php echo e(old('narahubung_adm')); ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Kontak Administrasi <span class="text-red-500">*</span></label>
                        <input type="text" name="nomor_cp_adm" value="<?php echo e(old('nomor_cp_adm')); ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Narahubung Teknis <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                        <input type="text" name="narahubung_teknis" value="<?php echo e(old('narahubung_teknis')); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Kontak Teknis <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                        <input type="text" name="nomor_cp_teknis" value="<?php echo e(old('nomor_cp_teknis')); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>
            </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" id="submitBtn" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-600">
                Simpan Kerja Sama
            </button>
            <a href="<?php echo e(route('mitra.kerjasama.index')); ?>" class="border border-gray-300 text-gray-700 px-6 py-2.5 rounded-lg text-sm hover:bg-gray-50">
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mitra', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/eltoruz/ProjekKP/resources/views/mitra/kerjasama/create.blade.php ENDPATH**/ ?>