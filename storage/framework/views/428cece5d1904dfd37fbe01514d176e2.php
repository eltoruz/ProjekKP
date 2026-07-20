<?php $__env->startSection('title', 'Daftar Kerja Sama'); ?>
<?php $__env->startSection('page-title', 'Daftar Kerja Sama'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap gap-3 justify-between items-center">
        <form method="GET" class="flex flex-wrap gap-3 items-center flex-1">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama, pihak, tentang..."
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:ring-blue-500 focus:border-blue-500">
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\KsStatusDok::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s->id); ?>" <?php echo e(request('status') == $s->id ? 'selected' : ''); ?>><?php echo e($s->nama_status); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </select>
            <select name="jenis" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Jenis</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $jenisList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($j->id); ?>" <?php echo e(request('jenis') == $j->id ? 'selected' : ''); ?>><?php echo e($j->nama_jenis); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filter</button>
        </form>
        <a href="<?php echo e(route('mitra.kerjasama.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">+ Tambah Baru</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mitra</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tentang</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Informasi Pengajuan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $kerjasamas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ks): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3"><?php echo e($loop->iteration + $kerjasamas->firstItem() - 1); ?></td>
                    <td class="px-4 py-3 font-medium"><?php echo e($ks->nama_kl ?? 'N/A'); ?></td>
                    <td class="px-4 py-3"><?php echo e($ks->jenis?->nama_jenis ?? '-'); ?></td>
                    <td class="px-4 py-3 max-w-xs truncate"><?php echo e($ks->tentang ?? '-'); ?></td>
                    <td class="px-4 py-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ks->ks_status_dok == 2): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$ks->tanggal_pembahasan): ?>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Menunggu Jadwal</span>
                            <?php elseif(!$ks->hasSuratUndangan()): ?>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Upload Undangan</span>
                            <?php else: ?>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700">Menunggu Pembahasan</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php elseif($ks->ks_status_dok): ?>
                            <?php
                                $sColors = [1 => 'bg-blue-100 text-blue-700', 3 => 'bg-orange-100 text-orange-700', 4 => 'bg-purple-100 text-purple-700', 5 => 'bg-green-100 text-green-700', 6 => 'bg-gray-100 text-gray-600'];
                                $sLabels = [1 => 'Menunggu Review', 3 => 'Pembahasan', 4 => 'Penandatanganan', 5 => 'Selesai', 6 => 'Berakhir'];
                            ?>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($sColors[$ks->ks_status_dok] ?? 'bg-gray-100 text-gray-600'); ?>">
                                <?php echo e($sLabels[$ks->ks_status_dok] ?? $ks->status_label); ?>

                            </span>
                        <?php else: ?>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Draft</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-gray-500"><?php echo e($ks->last_update?->format('d M Y')); ?></td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="<?php echo e(route('mitra.kerjasama.show', $ks->kerjasama_id)); ?>" class="text-blue-600 hover:text-blue-800 text-sm">Detail</a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($ks->ks_status_dok, [1, null], true)): ?>
                        <form action="<?php echo e(route('mitra.kerjasama.destroy', $ks->kerjasama_id)); ?>" method="POST" class="inline" onsubmit="return confirm('Hapus?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                        </form>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada data kerja sama.</td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-100">
        <?php echo e($kerjasamas->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mitra', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/eltoruz/ProjekKP/resources/views/mitra/kerjasama/index.blade.php ENDPATH**/ ?>