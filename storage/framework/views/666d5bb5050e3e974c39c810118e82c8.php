<span class="px-2.5 py-0.5 rounded-full text-xs font-medium
    <?php switch($status):
        case ('DRAFT'): ?> bg-gray-100 text-gray-700 <?php break; ?>
        <?php case ('UPLOAD_DOKUMEN'): ?> bg-gray-200 text-gray-800 <?php break; ?>
        <?php case ('DIAJUKAN'): ?> bg-blue-100 text-blue-700 <?php break; ?>
        <?php case ('REVIEW_ADMIN'): ?> bg-blue-200 text-blue-800 <?php break; ?>
        <?php case ('DITOLAK'): ?> bg-red-100 text-red-700 <?php break; ?>
        <?php case ('DISETUJUI'): ?> bg-yellow-100 text-yellow-700 <?php break; ?>
        <?php case ('MENUNGGU_PEMBAHASAN'): ?> bg-yellow-200 text-yellow-800 <?php break; ?>
        <?php case ('SELESAI_PEMBAHASAN'): ?> bg-purple-100 text-purple-700 <?php break; ?>
        <?php case ('PROSES_TTD'): ?> bg-purple-200 text-purple-800 <?php break; ?>
        <?php case ('SELESAI'): ?> bg-green-100 text-green-700 <?php break; ?>
        <?php case ('EXPIRED'): ?> bg-gray-200 text-gray-500 <?php break; ?>
        <?php default: ?> bg-gray-100 text-gray-600
    <?php endswitch; ?>
">
    <?php echo e($label ?? $status); ?>

</span>
<?php /**PATH /home/eltoruz/ProjekKP/resources/views/components/status-badge.blade.php ENDPATH**/ ?>