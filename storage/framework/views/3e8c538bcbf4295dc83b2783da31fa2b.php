<div class="w-full py-4">
    <div class="flex items-center justify-between">
        <?php
            $steps = [
                'DRAFT' => 'Draft',
                'UPLOAD_DOKUMEN' => 'Upload Dokumen',
                'DIAJUKAN' => 'Diajukan',
                'DITOLAK' => 'Ditolak',
                'DISETUJUI' => 'Disetujui',
                'MENUNGGU_PEMBAHASAN' => 'Menunggu Pembahasan',
                'SELESAI_PEMBAHASAN' => 'Selesai Pembahasan',
                'SELESAI' => 'Selesai',
                'EXPIRED' => 'Berakhir',
            ];
            $stepKeys = array_keys($steps);
            $currentIndex = array_search($current, $stepKeys);
            if ($currentIndex === false) $currentIndex = count($stepKeys) - 1;
            $displaySteps = $stepKeys;
        ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $displaySteps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $stepIdx = array_search($step, $stepKeys);
                $isActive = $stepIdx === $currentIndex;
                $isPassed = $stepIdx < $currentIndex;
                $isRejected = $step === 'DITOLAK' && $stepIdx === $currentIndex;
            ?>
            <div class="flex items-center <?php echo e(!$loop->last ? 'flex-1' : ''); ?>">
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold
                        <?php echo e($isActive || $isRejected ? 'bg-blue-600 text-white' : ($isPassed ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500')); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isPassed): ?> ✓ <?php else: ?> <?php echo e($stepIdx + 1); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <span class="text-xs mt-1 text-gray-500 whitespace-nowrap"><?php echo e($steps[$step]); ?></span>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$loop->last): ?>
                    <div class="flex-1 h-0.5 mx-2 <?php echo e($stepIdx < $currentIndex ? 'bg-green-500' : 'bg-gray-200'); ?>"></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH /home/eltoruz/ProjekKP/resources/views/components/workflow-stepper.blade.php ENDPATH**/ ?>