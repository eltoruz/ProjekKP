<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lampiran Data MoU — {{ $ks->nama_kl }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white; padding: 0; margin: 0; }
            .print-shadow-none { box-shadow: none !important; border: none !important; }
        }
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 p-4 md:p-8 min-h-screen">

    <div class="max-w-4xl mx-auto bg-white p-8 md:p-12 rounded-xl shadow-lg border border-slate-200 print-shadow-none">
        
        <div class="no-print flex items-center justify-between pb-6 mb-6 border-b border-slate-200">
            <a href="javascript:history.back()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                ← Kembali
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak / Simpan PDF
            </button>
        </div>

        <div class="flex items-center gap-5 border-b-2 border-slate-900 pb-5 mb-6">
            <img src="https://pelayanan.data.kemendikdasmen.go.id/assets/imge/tutwuri.png" class="h-16 w-auto" alt="Logo Tutwuri">
            <div>
                <h1 class="text-lg font-bold uppercase tracking-wider text-slate-900 leading-tight">KEMENTERIAN PENDIDIKAN DASAR DAN MENENGAH</h1>
                <h2 class="text-sm font-semibold text-slate-800">Pusat Data dan Teknologi Informasi (Pusdatin)</h2>
                <p class="text-xs text-slate-600">Jalan Jenderal Sudirman, Senayan, Jakarta 10270 | Website: pelayanan.data.kemendikdasmen.go.id</p>
            </div>
        </div>

        <div class="text-center my-6 space-y-1">
            <h3 class="text-base font-bold text-slate-900 uppercase tracking-wide underline">LAMPIRAN DAFTAR METADATA PEMANFAATAN DATA KERJA SAMA</h3>
            <p class="text-xs text-slate-600 font-mono">Nomor Dokumen: {{ $ks->nomor_pihak1 ?? $ks->nomor_pihak2 ?? $ks->kerjasama_id }}</p>
        </div>

        <div class="mb-6 bg-slate-50 p-4 rounded-lg border border-slate-200 text-xs space-y-2">
            <div class="grid grid-cols-3 gap-2">
                <span class="font-semibold text-slate-600">Instansi / Mitra Kerja Sama:</span>
                <span class="col-span-2 font-bold text-slate-900">{{ $ks->nama_kl }}</span>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <span class="font-semibold text-slate-600">Unit Kerja Pelaksana:</span>
                <span class="col-span-2 text-slate-900">{{ $ks->pihak2 ?? $ks->ttd_pihak2 ?? '-' }}</span>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <span class="font-semibold text-slate-600">Judul Nota Kesepakatan:</span>
                <span class="col-span-2 font-medium text-slate-900">{{ $ks->tentang ?? '-' }}</span>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <span class="font-semibold text-slate-600">Jangka Waktu Berlaku:</span>
                <span class="col-span-2 text-slate-900">
                    {{ $ks->jangka_waktu_thn ? $ks->jangka_waktu_thn . ' Tahun' : '-' }} 
                    ({{ $ks->tanggal_mulai_ks ? \Carbon\Carbon::parse($ks->tanggal_mulai_ks)->format('d M Y') : '-' }} s/d {{ $ks->tanggal_selesai_ks ? \Carbon\Carbon::parse($ks->tanggal_selesai_ks)->format('d M Y') : '-' }})
                </span>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <span class="font-semibold text-slate-600">Metode & Status Pertukaran:</span>
                <span class="col-span-2 text-slate-900 font-semibold">
                    {{ $ks->metode?->nama_metode ?? '-' }} — {{ $ks->implementasi?->nama_status ?? 'Belum Aktif' }}
                </span>
            </div>
        </div>

        <div class="space-y-3 mb-8">
            <h4 class="text-xs font-bold uppercase text-slate-900 tracking-wider">Rincian Kolom Data yang Disetujui (Approved)</h4>
            
            <table class="w-full text-left text-xs border-collapse border border-slate-300">
                <thead>
                    <tr class="bg-slate-200 text-slate-800 font-bold uppercase">
                        <th class="border border-slate-300 py-2 px-3 w-10 text-center">No</th>
                        <th class="border border-slate-300 py-2 px-3">Database & Tabel</th>
                        <th class="border border-slate-300 py-2 px-3">Nama Kolom (Field)</th>
                        <th class="border border-slate-300 py-2 px-3">Tipe Data</th>
                        <th class="border border-slate-300 py-2 px-3">Alasan Penggunaan</th>
                        <th class="border border-slate-300 py-2 px-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $no = 1; 
                        $approvedItems = $ks->pemilihanData->filter(fn($it) => $it->approval_status === 'approved');
                    @endphp
                    @forelse($approvedItems as $item)
                        @php $meta = $item->metadata; @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="border border-slate-300 py-2 px-3 text-center font-bold">{{ $no++ }}</td>
                            <td class="border border-slate-300 py-2 px-3 font-mono">
                                <strong>{{ $meta?->db_name ?? '-' }}</strong>.{{ $meta?->tbl_name ?? '-' }}
                            </td>
                            <td class="border border-slate-300 py-2 px-3 font-mono font-bold text-slate-900">
                                {{ $meta?->name ?? '-' }}
                            </td>
                            <td class="border border-slate-300 py-2 px-3 font-mono text-slate-600">
                                {{ $meta?->type_name ?? $meta?->type ?? 'varchar' }}
                            </td>
                            <td class="border border-slate-300 py-2 px-3 text-slate-700">
                                {{ $item->alasan ?? '-' }}
                            </td>
                            <td class="border border-slate-300 py-2 px-3 text-center">
                                <span class="bg-green-100 text-green-800 font-bold px-2 py-0.5 rounded text-[10px] border border-green-300">
                                    DISETUJUI
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border border-slate-300 py-6 text-center text-slate-500 italic">
                                Belum ada item data yang disetujui oleh Admin Pusdatin.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-12 grid grid-cols-2 gap-8 text-center text-xs pt-8 border-t border-slate-200">
            <div class="space-y-16">
                <p class="font-semibold text-slate-700">Pihak Pertama (Pusdatin Kemendikdasmen),</p>
                <div class="pt-8">
                    <p class="font-bold text-slate-900 underline">{{ $ks->ttd_pihak1 ?? 'Pusdatin Kemendikdasmen' }}</p>
                    <p class="text-[11px] text-slate-500">Penanggung Jawab Layanan Data</p>
                </div>
            </div>

            <div class="space-y-16">
                <p class="font-semibold text-slate-700">Pihak Kedua (Mitra Kerja Sama),</p>
                <div class="pt-8">
                    <p class="font-bold text-slate-900 underline">{{ $ks->ttd_pihak2 ?? $ks->nama_kl }}</p>
                    <p class="text-[11px] text-slate-500">{{ $ks->pihak2 ?? 'Perwakilan Mitra' }}</p>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-4 border-t border-slate-100 text-[10px] text-slate-400 text-center flex justify-between items-center">
            <span>Dokumen dicetak secara otomatis dari Sistem Manajemen Kerja Sama — Pusdatin Kemendikdasmen</span>
            <span>Tanggal Cetak: {{ date('d M Y, H:i') }}</span>
        </div>

    </div>

</body>
</html>
