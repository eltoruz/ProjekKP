<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
    <div class="flex items-center gap-3 mb-2">
        <div class="w-10 h-10 rounded-lg <?php echo e($bgColor ?? 'bg-blue-100'); ?> flex items-center justify-center">
            <?php echo $icon ?? ''; ?>

        </div>
        <p class="text-sm font-medium text-gray-500"><?php echo e($title); ?></p>
    </div>
    <p class="text-3xl font-bold text-gray-900"><?php echo e($value); ?></p>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($subtitle)): ?>
        <p class="text-xs text-gray-400 mt-1"><?php echo e($subtitle); ?></p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH /home/eltoruz/ProjekKP/resources/views/components/stat-card.blade.php ENDPATH**/ ?>