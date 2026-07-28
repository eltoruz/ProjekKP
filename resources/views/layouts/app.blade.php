<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistem Manajemen Kerja Sama') — Pusdatin Kemendikdasmen</title>
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
        [x-cloak] { display: none !important; }
    </style>
    <script>
        function formatJamInput(el) {
            let raw = el.value.replace(/[^0-9]/g, '');
            if (raw.length > 4) raw = raw.slice(0, 4);
            if (raw.length >= 3) {
                let h = parseInt(raw.slice(0, 2), 10);
                if (h > 23) h = 23;
                let hStr = h < 10 ? '0' + h : '' + h;
                let mRaw = raw.slice(2);
                let m = parseInt(mRaw, 10);
                if (!isNaN(m) && m > 59) m = 59;
                let mStr = raw.length === 3 ? mRaw : (!isNaN(m) ? (m < 10 ? '0' + m : '' + m) : mRaw);
                el.value = hStr + ':' + mStr;
            } else {
                el.value = raw;
            }
        }
        function validateJamOnBlur(el) {
            let raw = el.value.replace(/[^0-9]/g, '');
            if (raw.length === 4) {
                let h = parseInt(raw.slice(0, 2), 10);
                let m = parseInt(raw.slice(2), 10);
                if (h > 23) h = 23;
                if (m > 59) m = 59;
                el.value = (h < 10 ? '0' + h : h) + ':' + (m < 10 ? '0' + m : m);
            } else if (raw.length === 3) {
                let h = parseInt(raw.slice(0, 1), 10);
                let m = parseInt(raw.slice(1), 10);
                if (m > 59) m = 59;
                el.value = '0' + h + ':' + (m < 10 ? '0' + m : m);
            } else if (raw.length === 1 || raw.length === 2) {
                let h = parseInt(raw, 10);
                if (h > 23) h = 23;
                el.value = (h < 10 ? '0' + h : h) + ':00';
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen antialiased">
    <!-- Skip to Main Content Link (WCAG 2.1 Keyboard Navigation) -->
    <a href="#main-content" 
       class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50 focus:px-4 focus:py-2 focus:bg-indigo-600 focus:text-white focus:rounded-lg focus:shadow-lg focus:outline-none">
       Lompat ke Konten Utama
    </a>
    @yield('content')
</body>
</html>
