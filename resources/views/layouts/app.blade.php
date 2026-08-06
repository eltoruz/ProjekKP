<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistem Manajemen Kerja Sama') — Pusdatin Kemendikdasmen</title>
    <link rel="shortcut icon" href="https://pelayanan.data.kemendikdasmen.go.id/assets/imge/tutwuri.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
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

        window.KS_BULAN = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

        function ksDatePicker(initial, minDate, maxDate) {
            return {
                open: false,
                value: initial || '',
                inputRaw: initial || '',
                viewY: null,
                viewM: null,
                dayNames: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],

                toggle() {
                    this.open = !this.open;
                    if (this.open) this.resetView();
                },
                resetView() {
                    const base = this.parse(this.value) || this.parse(this.inputRaw) || new Date();
                    this.viewY = base.getFullYear();
                    this.viewM = base.getMonth();
                },
                parse(str) {
                    if (!str) return null;
                    str = String(str).trim().slice(0, 10);
                    let m = str.match(/^(\d{4})[-/](\d{1,2})[-/](\d{1,2})$/);
                    if (m) {
                        const d = new Date(+m[1], +m[2] - 1, +m[3]);
                        return isNaN(d.getTime()) ? null : d;
                    }
                    m = str.match(/^(\d{1,2})[-/](\d{1,2})[-/](\d{4})$/);
                    if (m) {
                        const d = new Date(+m[3], +m[2] - 1, +m[1]);
                        return isNaN(d.getTime()) ? null : d;
                    }
                    return null;
                },
                iso(y, m, d) {
                    return y + '-' + String(m + 1).padStart(2, '0') + '-' + String(d).padStart(2, '0');
                },
                init() {
                    this.resetView();
                    this.inputRaw = this.value;
                    this.$watch('value', (v) => {
                        this.inputRaw = v || '';
                        if (v) {
                            const d = this.parse(v);
                            if (d) {
                                this.viewY = d.getFullYear();
                                this.viewM = d.getMonth();
                            }
                        }
                    });
                },
                onTypeInput() {
                    const d = this.parse(this.inputRaw);
                    if (d) {
                        this.value = this.iso(d.getFullYear(), d.getMonth(), d.getDate());
                        this.viewY = d.getFullYear();
                        this.viewM = d.getMonth();
                    } else if (!this.inputRaw) {
                        this.value = '';
                    }
                },
                onBlurInput() {
                    const d = this.parse(this.inputRaw);
                    if (d) {
                        this.value = this.iso(d.getFullYear(), d.getMonth(), d.getDate());
                        this.inputRaw = this.value;
                    } else if (!this.inputRaw) {
                        this.value = '';
                        this.inputRaw = '';
                    }
                },
                setYear(year) {
                    this.viewY = parseInt(year);
                },
                shiftMonth(delta) {
                    let m = parseInt(this.viewM) + delta, y = parseInt(this.viewY);
                    if (m < 0) { m = 11; y--; }
                    if (m > 11) { m = 0; y++; }
                    this.viewM = m;
                    this.viewY = y;
                },
                pick(iso) {
                    this.value = iso;
                    this.inputRaw = iso;
                    this.open = false;
                },
                pickToday() {
                    const t = new Date();
                    this.viewY = t.getFullYear();
                    this.viewM = t.getMonth();
                    this.pick(this.iso(t.getFullYear(), t.getMonth(), t.getDate()));
                },
                get monthLabel() {
                    if (this.viewY === null) return '';
                    return window.KS_BULAN[this.viewM] + ' ' + this.viewY;
                },
                get display() {
                    const d = this.parse(this.value);
                    if (!d) return '';
                    return d.getDate() + ' ' + window.KS_BULAN[d.getMonth()] + ' ' + d.getFullYear();
                },
                get cells() {
                    if (this.viewY === null) this.resetView();
                    const y = parseInt(this.viewY);
                    const m = parseInt(this.viewM);
                    const first = new Date(y, m, 1);
                    const lead = (first.getDay() + 6) % 7;
                    const total = new Date(y, m + 1, 0).getDate();
                    const today = new Date();
                    const todayIso = this.iso(today.getFullYear(), today.getMonth(), today.getDate());
                    const selected = String(this.value).slice(0, 10);
                    const out = [];
                    for (let i = 0; i < lead; i++) out.push(null);
                    for (let d = 1; d <= total; d++) {
                        const iso = this.iso(y, m, d);
                        out.push({
                            day: d,
                            iso: iso,
                            isToday: iso === todayIso,
                            isSelected: iso === selected,
                            disabled: (minDate && iso < minDate) || (maxDate && iso > maxDate),
                        });
                    }
                    return out;
                },
            };
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen antialiased">
    <a href="#main-content" 
       class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50 focus:px-4 focus:py-2 focus:bg-indigo-600 focus:text-white focus:rounded-lg focus:shadow-lg focus:outline-none">
       Lompat ke Konten Utama
    </a>
    @yield('content')
</body>
</html>
