<?php $__env->startSection('title', 'Daftar Surat'); ?>
<?php $__env->startSection('page-title', 'Daftar Surat'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <form method="GET" class="flex gap-3 items-center">
            <select name="jenis" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Jenis Surat</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $jenisList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($j->id); ?>" <?php echo e(request('jenis') == $j->id ? 'selected' : ''); ?>><?php echo e($j->nama_jenis); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filter</button>
        </form>
        <a href="<?php echo e(route('mitra.surat.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">+ Tambah Surat</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Surat</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instansi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perihal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $surat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3"><?php echo e($loop->iteration + $surat->firstItem() - 1); ?></td>
                    <td class="px-4 py-3"><?php echo e($s->jenis?->nama_jenis); ?></td>
                    <td class="px-4 py-3 max-w-xs truncate"><?php echo e($s->nama_kl ?? '-'); ?></td>
                    <td class="px-4 py-3 max-w-xs truncate"><?php echo e($s->tentang ?? '-'); ?></td>
                    <td class="px-4 py-3"><?php echo e($s->tanggal_mulai_ks?->format('d M Y')); ?></td>
                    <td class="px-4 py-3 text-right">
                        <a href="<?php echo e(route('mitra.surat.show', $s->kerjasama_id)); ?>" class="text-blue-600 text-xs">Detail</a>
                        <a href="<?php echo e(route('mitra.surat.edit', $s->kerjasama_id)); ?>" class="text-yellow-600 text-xs ml-2">Edit</a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($s->dokumen_ks): ?>
                        <a href="<?php echo e(route('mitra.surat.download', $s->kerjasama_id)); ?>" class="text-blue-600 text-xs ml-2">Download</a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <form action="<?php echo e(route('mitra.surat.destroy', $s->kerjasama_id)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-600 text-xs ml-2" onclick="return confirm('Hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada surat.</td></tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100"><?php echo e($surat->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mitra', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/eltoruz/ProjekKP/resources/views/mitra/surat/index.blade.php ENDPATH**/ ?>