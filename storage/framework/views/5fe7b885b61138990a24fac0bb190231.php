<?php $__env->startSection('title', 'Daftar Kerja Sama'); ?>
<?php $__env->startSection('page-title', 'Daftar Kerja Sama'); ?>

<?php $__env->startSection('page-content'); ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm"><?php echo e(session('success')); ?></div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<div x-data="{
    search: '<?php echo e(request('search')); ?>',
    status: '<?php echo e(request('status')); ?>',
    jenis: '<?php echo e(request('jenis')); ?>',
    selectedIds: [],
    selectAll: false,
    toggleAll() {
        this.selectedIds = this.selectAll ? <?php echo e(Js::from($kerjasamas->pluck('kerjasama_id'))); ?> : [];
    },
    isSelected(id) { return this.selectedIds.includes(id); },
    confirmBulkDelete: false,
}"
    x-init="$watch('selectAll', v => toggleAll())">

    <!-- Filter Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Cari</label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Instansi / Perihal / No Input..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                    <option value="">Semua</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $statusList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($id); ?>" <?php echo e(request('status') == $id ? 'selected' : ''); ?>><?php echo e($name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Jenis</label>
                <select name="jenis" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                    <option value="">Semua</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $jenisList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($id); ?>" <?php echo e(request('jenis') == $id ? 'selected' : ''); ?>><?php echo e($name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-600">Filter</button>
                <a href="<?php echo e(route('admin.kerjasama.index')); ?>" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Reset</a>
                <a href="<?php echo e(route('admin.kerjasama.create')); ?>" class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-600 ml-auto">+ Tambah</a>
            </div>
        </form>
    </div>

    <!-- Bulk Action Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4" x-show="selectedIds.length > 0" x-cloak>
        <div class="flex items-center justify-between">
            <span class="text-sm text-gray-600"><span x-text="selectedIds.length"></span> item dipilih</span>
            <button @click="confirmBulkDelete = true" class="bg-red-500 text-white px-4 py-1.5 rounded-lg text-sm font-medium hover:bg-red-600">
                Hapus Terpilih
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-left">
                        <th class="px-4 py-3 w-10">
                            <input type="checkbox" x-model="selectAll" class="rounded border-gray-300">
                        </th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Mitra</th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Jenis</th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Tingkat</th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Jadwal</th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Update</th>
                        <th class="px-4 py-3 font-semibold text-gray-700 w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $kerjasamas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ks): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <input type="checkbox" value="<?php echo e($ks->kerjasama_id); ?>" x-model="selectedIds" class="rounded border-gray-300">
                        </td>
                        <td class="px-4 py-3 font-medium text-slate-800"><?php echo e($ks->nama_kl); ?></td>
                        <td class="px-4 py-3 text-gray-600"><?php echo e($ks->jenis->nama_jenis ?? '-'); ?></td>
                        <td class="px-4 py-3 text-gray-600"><?php echo e($ks->tingkat->nama_tingkat ?? '-'); ?></td>
                        <td class="px-4 py-3">
                            <?php
                                $statusColor = match((int)$ks->ks_status_dok) {
                                    1 => 'bg-blue-100 text-blue-700',
                                    2 => 'bg-yellow-100 text-yellow-700',
                                    3 => 'bg-orange-100 text-orange-700',
                                    4 => 'bg-purple-100 text-purple-700',
                                    5 => 'bg-green-100 text-green-700',
                                    6 => 'bg-gray-100 text-gray-600',
                                    default => 'bg-gray-100 text-gray-600',
                                };
                            ?>
                            <span class="inline-block px-2 py-0.5 rounded-full text-[11px] font-medium <?php echo e($statusColor); ?>"><?php echo e($ks->statusDok->nama_status ?? '-'); ?></span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs"><?php echo e($ks->tanggal_pembahasan?->format('d M Y, H:i') ?? '-'); ?></td>
                        <td class="px-4 py-3 text-gray-500 text-xs"><?php echo e($ks->last_update?->format('d M Y, H:i') ?? '-'); ?></td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <a href="<?php echo e(route('admin.kerjasama.review', $ks->kerjasama_id)); ?>"
                                    class="bg-amber-500 text-white px-2.5 py-1 rounded text-xs font-medium hover:bg-amber-600">Kelola</a>
                                <a href="<?php echo e(route('admin.kerjasama.edit', $ks->kerjasama_id)); ?>"
                                    class="bg-indigo-500 text-white px-2.5 py-1 rounded text-xs font-medium hover:bg-indigo-600">Edit</a>
                                <button type="button" onclick="if(confirm('Hapus data ini?')){document.getElementById('del-<?php echo e($ks->kerjasama_id); ?>').submit()}"
                                    class="bg-red-500 text-white px-2.5 py-1 rounded text-xs font-medium hover:bg-red-600">Hapus</button>
                                <form id="del-<?php echo e($ks->kerjasama_id); ?>" method="POST" action="<?php echo e(route('admin.kerjasama.destroy', $ks->kerjasama_id)); ?>" class="hidden"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?></form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Tidak ada data.</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200">
            <?php echo e($kerjasamas->links()); ?>

        </div>
    </div>

    <!-- Bulk Delete Modal -->
    <div x-show="confirmBulkDelete" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-transition>
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6" @click.outside="confirmBulkDelete = false">
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Hapus Terpilih</h3>
            <p class="text-sm text-gray-500 mb-4">Yakin hapus <span x-text="selectedIds.length"></span> data?</p>
            <div class="flex justify-end gap-2">
                <button @click="confirmBulkDelete = false" class="border border-gray-300 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Batal</button>
                <form method="POST" action="<?php echo e(route('admin.kerjasama.bulkDelete')); ?>" x-ref="bulkForm">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-600">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/eltoruz/ProjekKP/resources/views/admin/kerjasama/index.blade.php ENDPATH**/ ?>