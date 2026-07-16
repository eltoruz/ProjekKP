<?php $__env->startSection('title', 'Detail Kerja Sama'); ?>
<?php $__env->startSection('page-title', 'Detail Kerja Sama'); ?>

<?php $__env->startSection('page-content'); ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm"><?php echo e(session('success')); ?></div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm"><?php echo e(session('error')); ?></div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<div class="space-y-6" x-data="{ confirmAjukan: false, confirmHapus: false }">
    <!-- Status Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500">Status:</span>
                <?php if (isset($component)) { $__componentOriginal8c81617a70e11bcf247c4db924ab1b62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-badge','data' => ['status' => $kerjasama->status_pengajuan,'label' => $kerjasama->status_label]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($kerjasama->status_pengajuan),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($kerjasama->status_label)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c81617a70e11bcf247c4db924ab1b62)): ?>
<?php $attributes = $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62; ?>
<?php unset($__attributesOriginal8c81617a70e11bcf247c4db924ab1b62); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c81617a70e11bcf247c4db924ab1b62)): ?>
<?php $component = $__componentOriginal8c81617a70e11bcf247c4db924ab1b62; ?>
<?php unset($__componentOriginal8c81617a70e11bcf247c4db924ab1b62); ?>
<?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kerjasama->statusDok): ?>
                    <span class="text-xs text-gray-400">(<?php echo e($kerjasama->statusDok->nama_status); ?>)</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kerjasama->ks_jenis == 3): ?>
            <div class="flex gap-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canEdit && $kerjasama->status_pengajuan !== 'DITOLAK'): ?>
                    <a href="<?php echo e(route('mitra.kerjasama.edit', $kerjasama->kerjasama_id)); ?>" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Edit</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canEdit): ?>
                    <button type="button" @click="confirmHapus = true" class="border border-red-300 text-red-600 px-4 py-2 rounded-lg text-sm hover:bg-red-50">Hapus</button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kerjasama->status_pengajuan === 'UPLOAD_DOKUMEN'): ?>
                    <button type="button" @click="confirmAjukan = true" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">Ajukan ke Admin</button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kerjasama->status_pengajuan === 'DITOLAK'): ?>
                    <a href="<?php echo e(route('mitra.kerjasama.upload-ulang', $kerjasama->kerjasama_id)); ?>" class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-700">Upload Ulang</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- Modal Konfirmasi Ajukan -->
    <div x-show="confirmAjukan" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5)">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6" @click.outside="confirmAjukan = false">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Ajukan ke Admin?</h3>
                    <p class="text-sm text-gray-500">Setelah diajukan, data tidak dapat diubah lagi.</p>
                </div>
            </div>
            <div class="flex gap-3 justify-end">
                <button @click="confirmAjukan = false" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Batal</button>
                <form action="<?php echo e(route('mitra.kerjasama.ajukan', $kerjasama->kerjasama_id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">Ya, Ajukan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div x-show="confirmHapus" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5)">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6" @click.outside="confirmHapus = false">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Hapus Draft?</h3>
                    <p class="text-sm text-gray-500">Data yang dihapus tidak dapat dikembalikan.</p>
                </div>
            </div>
            <div class="flex gap-3 justify-end">
                <button @click="confirmHapus = false" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Batal</button>
                <form action="<?php echo e(route('mitra.kerjasama.destroy', $kerjasama->kerjasama_id)); ?>" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Workflow Timeline (hanya untuk NK) -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kerjasama->ks_jenis == 3): ?>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Progress Workflow</h3>
        <?php if (isset($component)) { $__componentOriginalfe7579874cc97a99f3f7ba86498f5966 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfe7579874cc97a99f3f7ba86498f5966 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workflow-stepper','data' => ['current' => $kerjasama->status_pengajuan]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workflow-stepper'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['current' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($kerjasama->status_pengajuan)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfe7579874cc97a99f3f7ba86498f5966)): ?>
<?php $attributes = $__attributesOriginalfe7579874cc97a99f3f7ba86498f5966; ?>
<?php unset($__attributesOriginalfe7579874cc97a99f3f7ba86498f5966); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfe7579874cc97a99f3f7ba86498f5966)): ?>
<?php $component = $__componentOriginalfe7579874cc97a99f3f7ba86498f5966; ?>
<?php unset($__componentOriginalfe7579874cc97a99f3f7ba86498f5966); ?>
<?php endif; ?>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Jadwal Pembahasan -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kerjasama->tanggal_pembahasan_ks): ?>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-base font-semibold text-navy mb-3">Jadwal Pembahasan</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
            <div><span class="text-gray-500">Tanggal:</span> <span class="font-medium"><?php echo e(\Carbon\Carbon::parse($kerjasama->tanggal_pembahasan_ks)->format('d M Y')); ?></span></div>
            <div><span class="text-gray-500">Jam:</span> <span class="font-medium"><?php echo e(substr($kerjasama->jam_pembahasan, 0, 5)); ?></span></div>
            <div><span class="text-gray-500">Lokasi:</span> <span class="font-medium"><?php echo e($kerjasama->lokasi_pembahasan ?? '-'); ?></span></div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kerjasama->link_meeting): ?>
            <div class="col-span-2"><span class="text-gray-500">Link Meeting:</span> <a href="<?php echo e($kerjasama->link_meeting); ?>" target="_blank" class="text-primary font-medium hover:underline"><?php echo e($kerjasama->link_meeting); ?></a></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kerjasama->pic_pembahasan): ?>
            <div><span class="text-gray-500">PIC:</span> <span class="font-medium"><?php echo e($kerjasama->pic_pembahasan); ?></span></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kerjasama->nomor_pihak1 || $kerjasama->nomor_pihak2): ?>
            <div><span class="text-gray-500">Nomor Pihak 1:</span> <span class="font-medium"><?php echo e($kerjasama->nomor_pihak1 ?? '-'); ?></span></div>
            <div><span class="text-gray-500">Nomor Pihak 2:</span> <span class="font-medium"><?php echo e($kerjasama->nomor_pihak2 ?? '-'); ?></span></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Data Final (setelah TTD) -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kerjasama->status_pengajuan === 'SELESAI'): ?>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-base font-semibold text-navy mb-3">Data Final Kerja Sama</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
            <div><span class="text-gray-500">Jangka Waktu:</span> <span class="font-medium"><?php echo e($kerjasama->jangka_waktu_thn ? $kerjasama->jangka_waktu_thn.' tahun' : '-'); ?></span></div>
            <div><span class="text-gray-500">Tanggal Mulai:</span> <span class="font-medium"><?php echo e($kerjasama->tanggal_mulai_ks?->format('d M Y') ?? '-'); ?></span></div>
            <div><span class="text-gray-500">Tanggal Berakhir:</span> <span class="font-medium"><?php echo e($kerjasama->tanggal_selesai_ks?->format('d M Y') ?? '-'); ?></span></div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kerjasama->metode): ?>
            <div><span class="text-gray-500">Metode:</span> <span class="font-medium"><?php echo e($kerjasama->metode->nama_metode); ?></span></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kerjasama->implementasi): ?>
            <div><span class="text-gray-500">Implementasi:</span> <span class="font-medium"><?php echo e($kerjasama->implementasi->nama_status); ?></span></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kerjasama->nomor_pihak1 || $kerjasama->nomor_pihak2): ?>
            <div><span class="text-gray-500">Nomor Pihak 1:</span> <span class="font-medium"><?php echo e($kerjasama->nomor_pihak1 ?? '-'); ?></span></div>
            <div><span class="text-gray-500">Nomor Pihak 2:</span> <span class="font-medium"><?php echo e($kerjasama->nomor_pihak2 ?? '-'); ?></span></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$kerjasama->ks_metode && !$kerjasama->jangka_waktu_thn): ?>
        <p class="text-xs text-yellow-600 mt-3">Menunggu finalisasi data oleh Admin Pusdatin.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Detail Data -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-gray-800">Informasi Kerja Sama</h3>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
            <div><span class="text-gray-500">Jenis:</span> <span class="font-medium"><?php echo e($kerjasama->jenis?->nama_jenis); ?></span></div>
            <div><span class="text-gray-500">Tingkat:</span> <span class="font-medium"><?php echo e($kerjasama->tingkat?->nama_tingkat); ?></span></div>
            <div><span class="text-gray-500">Provinsi:</span> <span class="font-medium"><?php echo e($kerjasama->provinsi ?? '-'); ?></span></div>
            <div><span class="text-gray-500">Kode Wilayah:</span> <span class="font-medium"><?php echo e($kerjasama->kode_wilayah ?? '-'); ?></span></div>
            <div><span class="text-gray-500">Instansi:</span> <span class="font-medium"><?php echo e($kerjasama->nama_kl ?? '-'); ?></span></div>
            <div><span class="text-gray-500">Jml K/L:</span> <span class="font-medium"><?php echo e($kerjasama->jumlah_kl_terlibat); ?></span></div>
            <div class="sm:col-span-2 lg:col-span-3">
                <span class="text-gray-500">Tentang:</span> <span class="font-medium"><?php echo e($kerjasama->tentang ?? '-'); ?></span>
            </div>
            <div><span class="text-gray-500">Pihak 1:</span> <span class="font-medium"><?php echo e($kerjasama->pihak1 ?? '-'); ?></span></div>
            <div><span class="text-gray-500">Pihak 2:</span> <span class="font-medium"><?php echo e($kerjasama->pihak2 ?? '-'); ?></span></div>
            <div><span class="text-gray-500">Jangka Waktu:</span> <span class="font-medium"><?php echo e($kerjasama->jangka_waktu_thn ? $kerjasama->jangka_waktu_thn.' tahun' : '-'); ?></span></div>
            <div><span class="text-gray-500">Tgl Mulai:</span> <span class="font-medium"><?php echo e($kerjasama->tanggal_mulai_ks?->format('d M Y') ?? '-'); ?></span></div>
            <div><span class="text-gray-500">Tgl Berakhir:</span> <span class="font-medium"><?php echo e($kerjasama->tanggal_selesai_ks?->format('d M Y') ?? '-'); ?></span></div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kerjasama->sisa_masa_berlaku): ?>
                <div><span class="text-gray-500">Sisa Masa Berlaku:</span> <span class="font-medium"><?php echo e($kerjasama->sisa_masa_berlaku); ?></span></div>
            <?php elseif($kerjasama->sisa_masa_berlaku_hari !== null): ?>
                <div><span class="text-gray-500">Sisa Masa Berlaku:</span> <span class="font-medium <?php echo e($kerjasama->sisa_masa_berlaku_hari < 30 ? 'text-red-600' : ''); ?>"><?php echo e($kerjasama->sisa_masa_berlaku_hari); ?> hari</span></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div><span class="text-gray-500">Narahubung Adm:</span> <span class="font-medium"><?php echo e($kerjasama->narahubung_adm ?? '-'); ?></span></div>
            <div><span class="text-gray-500">Kontak Adm:</span> <span class="font-medium"><?php echo e($kerjasama->nomor_cp_adm ?? '-'); ?></span></div>
            <div><span class="text-gray-500">Narahubung Teknis:</span> <span class="font-medium"><?php echo e($kerjasama->narahubung_teknis ?? '-'); ?></span></div>
            <div><span class="text-gray-500">Kontak Teknis:</span> <span class="font-medium"><?php echo e($kerjasama->nomor_cp_teknis ?? '-'); ?></span></div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kerjasama->metode): ?>
                <div><span class="text-gray-500">Metode:</span> <span class="font-medium"><?php echo e($kerjasama->metode->nama_metode); ?></span></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kerjasama->implementasi): ?>
                <div><span class="text-gray-500">Implementasi:</span> <span class="font-medium"><?php echo e($kerjasama->implementasi->nama_status); ?></span></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- Dokumen -->
    <?php $files = $kerjasama->dokumen_ks ? (json_decode($kerjasama->dokumen_ks, true) ?: []) : []; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($files)): ?>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200" x-data="{ open: false, src: '' }">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-gray-800">Dokumen</h3>
        </div>
        <div class="p-6">
            <ul class="divide-y divide-gray-100">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $labels = ['Surat Permohonan', 'Draft Nota Kesepakatan', 'Dokumen Bertanda Tangan'];
                    $label = $labels[$i] ?? 'Dokumen Revisi ke-'.($i - 2);
                ?>
                <li class="flex items-center gap-2 py-2">
                    <span class="text-sm"><?php echo e($label); ?></span>
                    <span class="text-gray-300">—</span>
                    <button type="button" @click="open = true; src = '<?php echo e(Storage::disk('public')->url($file)); ?>'" class="text-primary text-xs hover:underline">Lihat</button>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </ul>
        </div>

        <!-- Modal -->
        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.6)">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl h-[85vh] flex flex-col" @click.outside="open = false">
                <div class="flex justify-between items-center px-6 py-3 border-b">
                    <span class="font-semibold text-gray-700">Pratinjau Dokumen</span>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                </div>
                <iframe :src="src" class="flex-1 w-full rounded-b-xl" frameborder="0"></iframe>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Review Logs -->
    <?php $reviewLogs = $kerjasama->review_log; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($reviewLogs)): ?>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-3 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-navy">Riwayat Review</h3>
        </div>
        <div class="px-5 py-3 space-y-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $reviewLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $status = $log['status'] ?? '';
                $label = \App\Services\WorkflowService::STATUS[$status] ?? $status;
                $waktu = \Carbon\Carbon::parse($log['waktu'] ?? '')->format('d M Y, H:i');
                $isReject = $status === 'DITOLAK';
            ?>
            <div class="flex gap-2 text-xs">
                <span class="<?php echo e($isReject ? 'text-red-600' : 'text-green-600'); ?> font-medium flex-shrink-0"><?php echo e($label); ?></span>
                <span class="text-gray-400"><?php echo e($waktu); ?></span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log['alasan'] ?? null): ?>
                <span class="text-gray-500">— <?php echo e($log['alasan']); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log['catatan'] ?? null): ?>
                <span class="text-gray-400">(<?php echo e($log['catatan']); ?>)</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

    <!-- Upload Dokumen (langsung tampil, bukan modal) -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kerjasama->ks_jenis == 3 && $kerjasama->status_pengajuan === 'DRAFT'): ?>
    <div class="bg-white rounded-lg shadow-sm border-2 border-dashed border-primary/30 p-6">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
            <h3 class="text-base font-semibold text-navy">Upload Dokumen Nota Kesepakatan</h3>
        </div>
        <p class="text-sm text-gray-500 mb-4">Upload dua dokumen yang diperlukan: Surat Permohonan dan Draft Nota Kesepakatan.</p>
        <form action="<?php echo e(route('mitra.kerjasama.upload', $kerjasama->kerjasama_id)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">1. Surat Permohonan <span class="text-red-500">*</span></label>
                    <input type="file" name="surat_permohonan" accept=".pdf,.docx,.zip" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <p class="text-xs text-gray-400 mt-1">Surat dari Kepala Daerah ke Sekjen Kemendikdasmen</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">2. Draft Nota Kesepakatan <span class="text-red-500">*</span></label>
                    <input type="file" name="draft_nk" accept=".pdf,.docx,.zip" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <p class="text-xs text-gray-400 mt-1">Draft NK yang akan dibahas bersama</p>
                </div>
            </div>
            <p class="text-xs text-gray-400 mb-4">Format: PDF, DOCX, ZIP — Maks 20MB per file</p>
            <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-600">
                Upload Dokumen
            </button>
        </form>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mitra', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/eltoruz/ProjekKP/resources/views/mitra/kerjasama/show.blade.php ENDPATH**/ ?>