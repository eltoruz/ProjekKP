<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'Sistem Manajemen Kerja Sama'); ?> — Pusdatin Kemendikdasmen</title>
    <link rel="shortcut icon" href="https://pelayanan.data.kemendikdasmen.go.id/assets/imge/tutwuri.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#000637',
                        primary: '#1998ff',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">
    <?php echo $__env->yieldContent('content'); ?>
</body>
</html>
<?php /**PATH /home/eltoruz/ProjekKP/resources/views/layouts/app.blade.php ENDPATH**/ ?>