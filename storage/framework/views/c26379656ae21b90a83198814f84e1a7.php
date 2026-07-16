<?php $__env->startSection('title', 'Pilih Peran'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-navy to-blue-900">
    <div class="text-center">
        <div class="flex items-center justify-center gap-3 mb-8">
            <img src="https://pelayanan.data.kemendikdasmen.go.id/assets/imge/tutwuri.png" class="h-14" alt="">
            <div class="text-left">
                <h1 class="text-xl font-bold text-white">Pusdatin</h1>
                <p class="text-sm text-white/60">Kemendikdasmen</p>
            </div>
        </div>

        <h2 class="text-2xl font-semibold text-white mb-2">Sistem Manajemen Kerja Sama</h2>
        <p class="text-white/60 mb-10">Pemanfaatan Data Backbone</p>

        <div class="flex gap-4 justify-center">
            <a href="/mitra"
                class="bg-white text-navy px-10 py-4 rounded-xl font-semibold hover:bg-gray-100 transition shadow-lg">
                <div class="text-lg">Mitra</div>
                <div class="text-xs text-gray-500 font-normal mt-1">Kerja Sama</div>
            </a>
            <a href="/admin"
                class="bg-primary text-white px-10 py-4 rounded-xl font-semibold hover:bg-blue-600 transition shadow-lg">
                <div class="text-lg">Admin</div>
                <div class="text-xs text-white/70 font-normal mt-1">Pusdatin</div>
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/eltoruz/ProjekKP/resources/views/auth/role-selection.blade.php ENDPATH**/ ?>