<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['logs' => []]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['logs' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    use Illuminate\Support\Collection;
    $logs = $logs instanceof Collection ? $logs : collect($logs);
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logs->isEmpty()): ?>
    <span class="text-gray-400 text-sm">-</span>
<?php else: ?>
    <div class="py-1">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $label = $log->label ?? '';
                $time = $log->created_at ? $log->created_at->format('d M Y, H:i') : '-';
                $catatan = $log->catatan ?? null;
                $last = $i === $logs->count() - 1;

                $badgeClass = match($label) {
                    'Ditolak' => 'bg-red-100 text-red-700',
                    'Disetujui' => 'bg-green-100 text-green-700',
                    'Jadwal' => 'bg-blue-100 text-blue-700',
                    'TTD Selesai' => 'bg-purple-100 text-purple-700',
                    'Finalisasi' => 'bg-violet-100 text-violet-700',
                    default => 'bg-gray-100 text-gray-600',
                };
                $dotColor = match($label) {
                    'Ditolak' => 'bg-red-500',
                    'Disetujui' => 'bg-green-500',
                    'Jadwal' => 'bg-blue-500',
                    'TTD Selesai' => 'bg-purple-500',
                    'Finalisasi' => 'bg-violet-500',
                    default => 'bg-gray-400',
                };
            ?>
            <div class="flex gap-2.5">
                <div class="flex flex-col items-center shrink-0">
                    <div class="w-2.5 h-2.5 rounded-full <?php echo e($dotColor); ?> mt-1"></div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$last): ?>
                        <div class="w-0.5 flex-1 bg-gray-200 my-0.5"></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="<?php echo e($last ? '' : 'pb-3'); ?>">
                    <span class="inline-block px-2 py-0.5 rounded-full font-medium text-[11px] <?php echo e($badgeClass); ?>"><?php echo e($label); ?></span>
                    <span class="text-gray-400 text-[11px] ml-1.5"><?php echo e($time); ?></span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($catatan): ?>
                        <p class="text-gray-500 text-xs mt-1"><?php echo e($catatan); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH /home/eltoruz/ProjekKP/resources/views/components/review-log-timeline.blade.php ENDPATH**/ ?>