@extends('layouts.mitra')

@section('title', 'Pelaporan Berkala Data Mitra')
@section('page-title', 'Pelaporan Berkala Data Mitra')

@section('page-content')
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm font-medium flex items-center justify-between">
    <div class="flex items-center gap-2">
        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif

@if(session('error'))
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm font-medium flex items-center justify-between">
    <div class="flex items-center gap-2">
        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ session('error') }}</span>
    </div>
</div>
@endif

@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm font-medium">
    <div class="font-bold mb-1">Terjadi Kesalahan Validasi:</div>
    <ul class="list-disc list-inside text-xs space-y-0.5">
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

<script>
function laporanPageData(namaKl) {
    return {
        showModalTengah: false, 
        showModalAkhir: false, 
        showDetailModal: false,
        detailData: null,
        detailFilePath: null,
        detailPeriodeTahun: '',

        openDetailModal(reportId, filePath, periodeTahun) {
            try {
                const scriptEl = document.getElementById('report-json-' + reportId);
                this.detailData = scriptEl ? JSON.parse(scriptEl.textContent) : null;
            } catch (e) {
                console.error('Error parsing report json:', e);
                this.detailData = null;
            }
            this.detailFilePath = filePath;
            this.detailPeriodeTahun = periodeTahun;
            this.showDetailModal = true;
        },

        activeTahun: null,
        step: 1,
        errorMessage: '',
        
        nama_pemda: namaKl || '',
        jenis_pemda: 'Provinsi',
        unit_kerja: '',
        nama_pic: '',
        jabatan_pic: '',
        kontak_pic: '',
        email_pic: '',
        peran_mitra: '',
        
        program_kegiatan: '',
        tujuan_pemanfaatan: [],
        jenis_data: [],
        bentuk_pemanfaatan: [],
        publikasi_umum: '',
        media_publikasi: '',
        
        lokasi_pengolahan: [],
        ketersediaan_sistem: '',
        backup_praktik: '',
        integrasi_sistem: '',
        vendor_dependency: '',
        kendala_infrastruktur: '',
        
        kebijakan_keamanan: '',
        klasifikasi_data: '',
        hak_akses: '',
        logging_access: '',
        csirt_team: '',
        sop_insiden: '',
        kendala_insiden: '',
        
        rating_manfaat: 5,
        masukan_rekomendasi: '',
        pernyataan_kebenaran: false,
        
        openModalTengah(tahun, reportId, defaultRole) {
            this.activeTahun = tahun;
            this.step = 1;
            this.errorMessage = '';
            
            if (reportId) {
                try {
                    const scriptEl = document.getElementById('report-json-' + reportId);
                    if (scriptEl) {
                        const d = JSON.parse(scriptEl.textContent);
                        if (d && d.identitas) {
                            this.nama_pemda = d.identitas.nama_pemda || this.nama_pemda;
                            this.jenis_pemda = d.identitas.jenis_pemda || 'Provinsi';
                            this.unit_kerja = d.identitas.unit_kerja || '';
                            this.nama_pic = d.identitas.nama_pic || '';
                            this.jabatan_pic = d.identitas.jabatan_pic || '';
                            this.kontak_pic = d.identitas.kontak_pic || '';
                            this.email_pic = d.identitas.email_pic || '';
                            this.peran_mitra = d.identitas.peran_mitra || defaultRole || 'Pengelola Data';

                            this.program_kegiatan = d.pemanfaatan?.program_kegiatan || '';
                            this.tujuan_pemanfaatan = Array.isArray(d.pemanfaatan?.tujuan_pemanfaatan) ? d.pemanfaatan.tujuan_pemanfaatan : [];
                            this.jenis_data = Array.isArray(d.pemanfaatan?.jenis_data) ? d.pemanfaatan.jenis_data : [];
                            this.bentuk_pemanfaatan = Array.isArray(d.pemanfaatan?.bentuk_pemanfaatan) ? d.pemanfaatan.bentuk_pemanfaatan : [];
                            this.publikasi_umum = d.pemanfaatan?.publikasi_umum || '';
                            this.media_publikasi = d.pemanfaatan?.media_publikasi || '';

                            this.lokasi_pengolahan = Array.isArray(d.infrastruktur?.lokasi_pengolahan) ? d.infrastruktur.lokasi_pengolahan : [];
                            this.ketersediaan_sistem = d.infrastruktur?.ketersediaan_sistem || '';
                            this.backup_praktik = d.infrastruktur?.backup_praktik || '';
                            this.integrasi_sistem = d.infrastruktur?.integrasi_sistem || '';
                            this.vendor_dependency = d.infrastruktur?.vendor_dependency || '';
                            this.kendala_infrastruktur = d.infrastruktur?.kendala_infrastruktur || '';

                            this.kebijakan_keamanan = d.keamanan?.kebijakan_keamanan || '';
                            this.klasifikasi_data = d.keamanan?.klasifikasi_data || '';
                            this.hak_akses = d.keamanan?.hak_akses || '';
                            this.logging_access = d.keamanan?.logging_access || '';
                            this.csirt_team = d.keamanan?.csirt_team || '';
                            this.sop_insiden = d.keamanan?.sop_insiden || '';
                            this.kendala_insiden = d.keamanan?.kendala_insiden || '';

                            this.rating_manfaat = d.evaluasi?.rating_manfaat || 5;
                            this.masukan_rekomendasi = d.evaluasi?.masukan_rekomendasi || '';
                        }
                    }
                } catch (e) {
                    console.error('Error prefilling form data:', e);
                }
            } else {
                this.peran_mitra = defaultRole || 'Pengelola Data';
                this.program_kegiatan = '';
                this.ketersediaan_sistem = '';
                this.kebijakan_keamanan = '';
            }
            this.showModalTengah = true;
        },
        nextStep() {
            this.errorMessage = '';
            if (this.step === 1) {
                if (!this.nama_pemda || !this.nama_pic || !this.kontak_pic || !this.email_pic) {
                    this.errorMessage = 'Mohon lengkapi data Identitas Pelapor (Nama Pemda, Nama PIC, Kontak, dan Email wajib diisi).';
                    return;
                }
            } else if (this.step === 2) {
                if (this.peran_mitra === 'Pengelola Data' || !this.peran_mitra) {
                    if (!this.program_kegiatan) {
                        this.errorMessage = 'Mohon isi Program/Kegiatan Pemanfaatan Data (Q10).';
                        return;
                    }
                } else if (this.peran_mitra === 'Pengelola Infrastruktur') {
                    if (!this.ketersediaan_sistem) {
                        this.errorMessage = 'Mohon pilih Tingkat Ketersediaan Sistem / Infrastruktur (Q17).';
                        return;
                    }
                } else if (this.peran_mitra === 'Pengelola Keamanan Data') {
                    if (!this.kebijakan_keamanan) {
                        this.errorMessage = 'Mohon pilih Kebijakan Internal Terkait Keamanan Data (Q22).';
                        return;
                    }
                }
            } else if (this.step === 3) {
                if (!this.rating_manfaat) {
                    this.errorMessage = 'Mohon berikan Rating Manfaat.';
                    return;
                }
            }
            if (this.step < 4) this.step++;
        },
        prevStep() {
            this.errorMessage = '';
            if (this.step > 1) this.step--;
        }
    };
}
</script>

<div class="space-y-6" x-data='laporanPageData(@json($ks->nama_kl ?? ""))'>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('mitra.kerjasama.index') }}" class="hover:text-indigo-600">Kerja Sama</a>
                <span>/</span>
                <a href="{{ route('mitra.kerjasama.show', $ks->kerjasama_id) }}" class="hover:text-indigo-600">{{ Str::limit($ks->nama_kl, 30) }}</a>
                <span>/</span>
                <span class="text-gray-800 font-medium">Pelaporan Berkala</span>
            </div>
            <div class="flex items-center gap-3">
                <h2 class="text-xl font-bold text-gray-900">Pelaporan Berkala Penggunaan Data</h2>
                @if($isReportingActive)
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-300 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Pelaporan Aktif
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300 inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Belum Aktif
                    </span>
                @endif
            </div>
            <p class="text-xs text-gray-500 mt-0.5">Mitra dapat mengisikan laporan penggunaan data untuk Laporan Tengah Tahun dan Laporan Akhir Tahun.</p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('mitra.kerjasama.show', $ks->kerjasama_id) }}" 
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-gray-300 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-colors shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Detail
            </a>
        </div>
    </div>

    @if(!$isReportingActive)
    <div class="bg-amber-50 border border-amber-300 rounded-xl p-5 text-amber-900 shadow-xs space-y-3">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div>
                <strong class="text-sm font-bold block mb-1">Fitur Upload Laporan Berkala Belum Aktif</strong>
                <p class="text-xs text-amber-800">
                    Menu unggah laporan akan terbuka secara otomatis jika kedua syarat berikut telah terpenuhi oleh Admin Pusdatin:
                </p>
                <ul class="mt-2 space-y-1 text-xs font-medium">
                    <li class="flex items-center gap-2">
                        @if($approvedItems->count() > 0)
                            <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Minimal 1 item data disetujui Admin (Terpenuhi: {{ $approvedItems->count() }} item approved)
                        @else
                            <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Minimal 1 item data disetujui Admin (Belum terpenuhi)
                        @endif
                    </li>
                    <li class="flex items-center gap-2">
                        @if((int)$ks->ks_implementasi === 3)
                            <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Status Implementasi Layanan / Pertukaran Data diset 'Aktif' (Terpenuhi)
                        @else
                            <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Status Implementasi Layanan diset 'Aktif' oleh Admin (Status saat ini: <strong>{{ $ks->implementasi?->nama_status ?? 'Belum Aktif' }}</strong>)
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-200 bg-slate-50 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Kartu Pelaporan Berdasarkan Jangka Waktu</h3>
                <p class="text-xs text-gray-500 mt-0.5">
                    @if(count($periodeList) > 0)
                        Jangka waktu kerja sama {{ $ks->jangka_waktu_thn }} tahun (Terdapat {{ count($periodeList) }} Kartu Pelaporan Tahun)
                    @else
                        Jumlah kartu tahun dihitung dari Jangka Waktu dan Tanggal Mulai kerja sama
                    @endif
                </p>
            </div>
        </div>

        @if(count($periodeList) === 0)
            <div class="p-8 text-center text-gray-500 text-xs">
                <p class="font-medium text-gray-700 mb-1">Jangka waktu pelaporan belum dapat ditentukan.</p>
                <p>Kartu pelaporan tahunan akan muncul otomatis setelah Admin mengisi Jangka Waktu dan Tanggal Mulai pada tahap finalisasi kerja sama.</p>
            </div>
        @else
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($periodeList as $card)
                <div class="border border-gray-200 rounded-xl bg-white shadow-xs overflow-hidden flex flex-col justify-between">
                    <div>
                        
                        <div class="bg-slate-100/80 px-5 py-3.5 border-b border-gray-200 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <h4 class="text-sm font-bold text-gray-900">Tahun ke-{{ $card['tahun_ke'] }} ({{ $card['tahun'] }})</h4>
                            </div>
                            <span class="text-[11px] font-semibold text-gray-500 bg-white px-2.5 py-1 rounded-md border border-gray-200">
                                Periode {{ $card['tahun'] }}
                            </span>
                        </div>

                        <div class="p-5 space-y-4">
                            
                            @php $tengah = $card['periodes']['Tengah Tahun']; @endphp
                            <div x-data="{ openTengah: false }" class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 space-y-2.5 transition-all">
                                
                                <button type="button" @click="openTengah = !openTengah"
                                        class="w-full flex items-center justify-between text-left focus:outline-none cursor-pointer group">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h5 class="text-xs font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">Laporan Tengah Tahun</h5>
                                                <span class="text-[10px] text-gray-500 font-normal">({{ $tengah['rentang'] }})</span>
                                            </div>
                                            <p class="text-[11px] text-gray-500">Klik untuk opsi & pengisian 3 Peran</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        @if(($tengah['completed_roles_count'] ?? 0) >= 3)
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 border border-green-300">
                                                Selesai (3/3 Peran)
                                            </span>
                                        @elseif(($tengah['completed_roles_count'] ?? 0) > 0)
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                                Progres ({{ $tengah['completed_roles_count'] }}/3 Peran)
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-700 border border-slate-300">
                                                Belum Mengisi (0/3 Peran)
                                            </span>
                                        @endif

                                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0" :class="openTengah ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </button>

                                <div x-show="openTengah" x-cloak x-collapse class="space-y-2 pt-2 border-t border-slate-200">
                                    @foreach(['Pengelola Data', 'Pengelola Infrastruktur', 'Pengelola Keamanan Data'] as $roleName)
                                        @php $rReport = $tengah['role_reports'][$roleName] ?? null; @endphp
                                        <div class="p-2.5 bg-white rounded-lg border border-gray-200 space-y-2">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full {{ $rReport ? 'bg-green-500' : 'bg-amber-400' }}"></span>
                                                    <strong class="text-xs text-gray-800 font-bold">{{ $roleName }}</strong>
                                                </div>
                                                @if($rReport)
                                                    <span class="text-[10px] font-bold px-2 py-0.5 bg-green-50 text-green-700 border border-green-200 rounded">Sudah Diisi</span>
                                                @else
                                                    <span class="text-[10px] font-medium px-2 py-0.5 bg-gray-50 text-gray-500 border border-gray-200 rounded">Belum Diisi</span>
                                                @endif
                                            </div>

                                            @if($rReport)
                                                <script id="report-json-{{ $rReport->id }}" type="application/json">{!! $rReport->catatan !!}</script>
                                                <div class="flex flex-wrap items-center justify-between gap-2 pt-1 border-t border-gray-100 text-[11px]">
                                                    <span class="text-gray-500 text-[10px]">Dikirim {{ $rReport->created_at?->format('d M Y') ?? '-' }}</span>
                                                    <div class="flex items-center gap-2">
                                                        <button type="button" @click.stop="openDetailModal('{{ $rReport->id }}', '{{ $rReport->file_path ? Storage::disk('public')->url($rReport->file_path) : '' }}', '{{ $card['tahun'] }} - {{ $roleName }}')"
                                                                class="text-indigo-600 hover:text-indigo-800 font-semibold inline-flex items-center gap-1 cursor-pointer">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                            Lihat Kuesioner
                                                        </button>
                                                        @if($rReport->file_path)
                                                            <a href="{{ Storage::disk('public')->url($rReport->file_path) }}" target="_blank"
                                                               class="text-emerald-600 hover:text-emerald-800 font-semibold inline-flex items-center gap-1">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                                Unduh Surat
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            @if($isReportingActive)
                                                <button type="button" 
                                                        @click.stop="openModalTengah({{ $card['tahun'] }}, '{{ $rReport?->id }}', '{{ $roleName }}')"
                                                        class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-semibold {{ $rReport ? 'text-gray-700 bg-gray-50 hover:bg-gray-100 border border-gray-200' : 'text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200' }} transition-colors shadow-2xs cursor-pointer">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    {{ $rReport ? "Edit Kuesioner ({$roleName})" : "Isi Form {$roleName}" }}
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @php $akhir = $card['periodes']['Akhir Tahun']; @endphp
                            <div class="p-4 rounded-lg border border-slate-200 bg-slate-50/50 space-y-2">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h5 class="text-xs font-bold text-gray-800">Laporan Akhir Tahun</h5>
                                        <p class="text-[11px] text-gray-500">{{ $akhir['rentang'] }}</p>
                                    </div>
                                    @if($akhir['laporan'])
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 border border-green-300 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Terkirim
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-700 border border-slate-300">
                                            Belum Mengisi
                                        </span>
                                    @endif
                                </div>

                                @if($akhir['laporan'])
                                    <div class="pt-2 text-[11px] text-gray-600 border-t border-slate-200 flex items-center justify-between">
                                        <span class="truncate max-w-[180px]" title="{{ $akhir['laporan']->nama_file }}">{{ $akhir['laporan']->nama_file }}</span>
                                        @if($akhir['laporan']->file_path)
                                            <a href="{{ Storage::disk('public')->url($akhir['laporan']->file_path) }}" target="_blank"
                                               class="inline-flex items-center gap-1 text-indigo-600 hover:underline font-semibold shrink-0">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                Unduh File
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                @if($isReportingActive)
                                <button type="button" 
                                        @click.stop="activeTahun = {{ $card['tahun'] }}; showModalAkhir = true"
                                        class="w-full mt-2 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 transition-colors shadow-2xs cursor-pointer">
                                    <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    {{ $akhir['laporan'] ? 'Unggah Ulang Dokumen Akhir Tahun' : 'Unggah Dokumen Akhir Tahun' }}
                                </button>
                                @else
                                <button type="button" disabled
                                        class="w-full mt-2 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-gray-400 bg-gray-100 border border-gray-200 cursor-not-allowed"
                                        title="Syarat pelaporan belum aktif: Minimal 1 item data disetujui Admin dan Status Implementasi diset Aktif.">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    Unggah Akhir Tahun (Belum Aktif)
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-200 flex items-center justify-between bg-slate-50">
            <h3 class="text-sm font-bold text-gray-900">Riwayat Pelaporan Berkala Terkirim ({{ $ks->reports->count() }})</h3>
        </div>

        @if($ks->reports->count() === 0)
            <div class="p-8 text-center text-gray-500 text-xs">
                <p class="font-medium text-gray-700 mb-1">Belum ada laporan berkala yang dikirim.</p>
                <p>Laporan yang dikirim akan tercatat secara historis di tabel ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-gray-100/80 border-b border-gray-200 text-gray-700 font-bold uppercase">
                            <th class="py-3 px-4">Tahun / Periode</th>
                            <th class="py-3 px-4">Nama File / Keterangan</th>
                            <th class="py-3 px-4">Tanggal Unggah</th>
                            <th class="py-3 px-4">Catatan</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($ks->reports as $rep)
                        @php
                            $catatanData = null;
                            if (!empty($rep->catatan)) {
                                $decoded = json_decode($rep->catatan, true);
                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded['is_questionnaire'])) {
                                    $catatanData = $decoded;
                                }
                            }
                            $repFileUrl = $rep->file_path ? (Storage::disk('public')->exists($rep->file_path) ? Storage::disk('public')->url($rep->file_path) : asset('storage/' . $rep->file_path)) : '';
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4 font-bold text-gray-900">
                                <span class="px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200 text-xs">
                                    {{ $rep->periode }} {{ $rep->tahun }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-medium text-gray-800">{{ $rep->nama_file }}</td>
                            <td class="py-3 px-4 text-gray-500">{{ $rep->created_at?->format('d M Y, H:i') ?? '-' }}</td>
                            <td class="py-3 px-4 text-gray-600">
                                @if($catatanData)
                                    <script id="report-json-{{ $rep->id }}" type="application/json">@json($catatanData)</script>
                                    <button type="button" 
                                            @click="openDetailModal('{{ $rep->id }}', '{{ $repFileUrl }}', '{{ $rep->periode }} {{ $rep->tahun }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition-colors cursor-pointer shadow-2xs">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Lihat Detail Kuesioner
                                    </button>
                                @else
                                    <span class="whitespace-pre-line">{{ $rep->catatan ?? '-' }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($rep->file_path)
                                <a href="{{ $repFileUrl }}" target="_blank"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Unduh File
                                </a>
                                @else
                                <span class="text-gray-400 font-mono text-[11px]">Form Terisi</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div x-show="showModalTengah" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(15, 23, 42, 0.6)" @keydown.escape.window="showModalTengah = false" @click.self="showModalTengah = false">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[92vh] flex flex-col overflow-hidden border border-gray-200">

            <div class="bg-slate-900 text-white p-5 border-b border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-600 rounded-lg text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-bold text-white">Kuesioner Laporan Tengah Tahun</h3>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">Tahun <span x-text="activeTahun"></span></span>
                        </div>
                        <p class="text-xs text-slate-300 mt-0.5">Lengkapi kuesioner sesuai peran yang Anda tangani di Pemda.</p>
                    </div>
                </div>
                <button type="button" @click="showModalTengah = false" class="text-slate-400 hover:text-white transition-colors p-1 rounded-lg hover:bg-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                
                <div class="grid grid-cols-4 gap-2 mb-3 text-center">
                    <template x-for="(st, idx) in [
                        { num: 1, label: '1. Identitas' },
                        { num: 2, label: peran_mitra === 'Pengelola Infrastruktur' ? '2. Infrastruktur' : (peran_mitra === 'Pengelola Keamanan Data' ? '2. Keamanan Data' : '2. Pemanfaatan') },
                        { num: 3, label: '3. Evaluasi' },
                        { num: 4, label: '4. Ringkasan' }
                    ]" :key="idx">
                        <div class="flex flex-col items-center">
                            <div :class="{
                                'bg-indigo-600 text-white font-bold ring-4 ring-indigo-100': step === st.num,
                                'bg-green-600 text-white font-bold': step > st.num,
                                'bg-gray-200 text-gray-500 font-medium': step < st.num
                            }" class="w-7 h-7 rounded-full flex items-center justify-center text-xs transition-all duration-200">
                                <span x-show="step <= st.num" x-text="st.num"></span>
                                <svg x-show="step > st.num" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-[11px] font-semibold mt-1 truncate w-full" :class="step === st.num ? 'text-indigo-600 font-bold' : (step > st.num ? 'text-gray-800' : 'text-gray-400')" x-text="st.label"></span>
                        </div>
                    </template>
                </div>
                
                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                    <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" :style="`width: ${Math.round((step / 4) * 100)}%`"></div>
                </div>
                <div class="flex justify-between items-center text-[11px] text-gray-500 mt-1 font-medium">
                    <span>Langkah <span x-text="step"></span> dari 4</span>
                    <span x-text="`${Math.round((step / 4) * 100)}% Selesai`"></span>
                </div>
            </div>

            <div x-show="errorMessage" x-cloak class="mx-6 mt-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-xs font-semibold flex items-center gap-2">
                <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span x-text="errorMessage"></span>
            </div>

            <form action="{{ route('mitra.kerjasama.laporan.store', $ks->kerjasama_id) }}" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto p-6 space-y-6">
                @csrf
                <input type="hidden" name="tahun" :value="activeTahun">
                <input type="hidden" name="periode" value="Tengah Tahun">
                <input type="hidden" name="peran_mitra" :value="peran_mitra">

                <div x-show="step === 1" class="space-y-4">
                    <div class="border-b border-gray-200 pb-3">
                        <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded-full font-bold">1</span>
                            Identitas Pelapor
                        </h4>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Informasi instansi, penanggung jawab teknis, dan dokumen pendukung disposisi untuk peran 
                            <strong class="text-indigo-600 font-bold" x-text="peran_mitra"></strong>.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Q1. Nama Pemda / Instansi Mitra <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_pemda" x-model="nama_pemda" placeholder="Contoh: Pemerintah Provinsi DKI Jakarta" required
                                   class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-indigo-500 bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Q2. Jenis Pemerintah Daerah <span class="text-red-500">*</span></label>
                            <select name="jenis_pemda" x-model="jenis_pemda" required
                                    class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-indigo-500 bg-white">
                                <option value="Provinsi">Provinsi</option>
                                <option value="Kabupaten">Kabupaten</option>
                                <option value="Kota">Kota</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Q3. Unit Kerja / OPD Penanggung Jawab</label>
                            <input type="text" name="unit_kerja" x-model="unit_kerja" placeholder="Contoh: Dinas Komunikasi dan Informatika"
                                   class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-indigo-500 bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Q4. Nama PIC / Penanggung Jawab Teknis <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_pic" x-model="nama_pic" placeholder="Nama lengkap PIC" required
                                   class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-indigo-500 bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Q5. Jabatan PIC</label>
                            <input type="text" name="jabatan_pic" x-model="jabatan_pic" placeholder="Contoh: Kepala Bidang Data & Informasi"
                                   class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-indigo-500 bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Q6. No. Telepon / WhatsApp PIC <span class="text-red-500">*</span></label>
                            <input type="text" name="kontak_pic" x-model="kontak_pic" placeholder="08xxxxxxxxxx" required
                                   class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-indigo-500 bg-white">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Q7. Email Resmi / PIC <span class="text-red-500">*</span></label>
                            <input type="email" name="email_pic" x-model="email_pic" placeholder="email@pemda.go.id" required
                                   class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-indigo-500 bg-white">
                        </div>

                        <div class="md:col-span-2 bg-indigo-50/60 p-4 rounded-xl border border-indigo-100 space-y-2">
                            <label class="block text-xs font-bold text-indigo-900">
                                Q9. Upload Surat Disposisi / Surat Tugas / SK Penunjukan (Opsional)
                            </label>
                            <p class="text-[11px] text-indigo-700">Format PDF, JPG, JPEG, PNG (Maksimal 10MB).</p>
                            <input type="file" name="file_disposisi" accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full text-xs text-gray-700 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                        </div>
                    </div>
                </div>

                
                <div x-show="step === 2 && (peran_mitra === 'Pengelola Data' || !peran_mitra)" class="space-y-4">
                    <div class="border-b border-gray-200 pb-3">
                        <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded-full font-bold">2</span>
                            Pengelola Data - Pemanfaatan & Publikasi (Q10 - Q15)
                        </h4>
                        <p class="text-xs text-gray-500 mt-0.5">Informasi mengenai program kegiatan, tujuan, bentuk pemanfaatan, serta keterbukaan publikasi data.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Q10. Program / Kegiatan Utama Pemanfaatan Data <span class="text-red-500">*</span></label>
                        <textarea name="program_kegiatan" x-model="program_kegiatan" rows="3" placeholder="Jelaskan program atau kegiatan utama yang memanfaatkan data dari kerja sama ini..."
                                  class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-indigo-500 bg-white"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Q11. Tujuan Utama Pemanfaatan Data (Dapat memilih lebih dari satu)</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                            <template x-for="item in ['Perencanaan program/kegiatan', 'Monitoring & evaluasi', 'Penetapan sasaran (verifikasi)', 'Pelaporan']">
                                <label class="flex items-center gap-2 p-2.5 rounded-lg border border-gray-200 hover:bg-slate-50 cursor-pointer">
                                    <input type="checkbox" name="tujuan_pemanfaatan[]" :value="item" x-model="tujuan_pemanfaatan" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span x-text="item" class="text-gray-700"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Q12. Jenis Data yang Digunakan dalam Pemanfaatan</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                            <template x-for="item in ['Data Satuan Pendidikan', 'Data Peserta Didik', 'Data Pendidik dan Tenaga Kependidikan (PTK)', 'Data Alat', 'Data Angkutan', 'Data Bangunan', 'Data Buku', 'Data Pembelajaran', 'Data Rombongan Belajar', 'Data Ruang', 'Data Sanitasi', 'Data Tanah', 'Data Rekap per Satuan Pendidikan', 'Data Anak Tidak Sekolah (ATS)']">
                                <label class="flex items-center gap-2 p-2.5 rounded-lg border border-gray-200 hover:bg-slate-50 cursor-pointer">
                                    <input type="checkbox" name="jenis_data[]" :value="item" x-model="jenis_data" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span x-text="item" class="text-gray-700"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Q13. Bentuk Pemanfaatan Data</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                            <template x-for="item in ['Analisis Internal', 'Dasbor Internal', 'Laporan Internal', 'Bahan Kebijakan']">
                                <label class="flex items-center gap-2 p-2.5 rounded-lg border border-gray-200 hover:bg-slate-50 cursor-pointer">
                                    <input type="checkbox" name="bentuk_pemanfaatan[]" :value="item" x-model="bentuk_pemanfaatan" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span x-text="item" class="text-gray-700"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Q14. Apakah Data / Hasil Olahan Dipublikasikan ke Khalayak Umum?</label>
                        <div class="flex flex-wrap gap-4 text-xs">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="publikasi_umum" value="Tidak dipublikasikan" x-model="publikasi_umum" class="text-indigo-600 focus:ring-indigo-500">
                                <span>Tidak dipublikasikan</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="publikasi_umum" value="Ya, dalam bentuk agregat" x-model="publikasi_umum" class="text-indigo-600 focus:ring-indigo-500">
                                <span>Ya, dalam bentuk agregat</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="publikasi_umum" value="Ya, dalam bentuk laporan" x-model="publikasi_umum" class="text-indigo-600 focus:ring-indigo-500">
                                <span>Ya, dalam bentuk laporan</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Q15. Media Publikasi yang Digunakan (jika ada)</label>
                        <div class="flex flex-wrap gap-4 text-xs">
                            <template x-for="item in ['Tidak dipublikasikan', 'Website resmi Pemda', 'Laporan cetak/digital', 'Media sosial']">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="media_publikasi" :value="item" x-model="media_publikasi" class="text-indigo-600 focus:ring-indigo-500">
                                    <span x-text="item"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>

                <div x-show="step === 2 && peran_mitra === 'Pengelola Infrastruktur'" class="space-y-4">
                    <div class="border-b border-gray-200 pb-3">
                        <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded-full font-bold">2</span>
                            Pengelola Infrastruktur - Kondisi Pendukung (Q16 - Q21)
                        </h4>
                        <p class="text-xs text-gray-500 mt-0.5">Kondisi lingkungan IT, server pengolahan, ketersediaan jaringan, dan integrasi sistem.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Q16. Lokasi / Environment Server Pengolahan & Penyimpanan Data</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                            <template x-for="item in ['Data Center Pemerintah Daerah', 'Cloud Pemerintah', 'Cloud Pihak Ketiga', 'Kombinasi']">
                                <label class="flex items-center gap-2 p-2.5 rounded-lg border border-gray-200 hover:bg-slate-50 cursor-pointer">
                                    <input type="checkbox" name="lokasi_pengolahan[]" :value="item" x-model="lokasi_pengolahan" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span x-text="item" class="text-gray-700"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Q17. Tingkat Ketersediaan Sistem / Infrastruktur <span class="text-red-500">*</span></label>
                        <div class="space-y-2 text-xs">
                            <template x-for="item in ['Selalu tersedia', 'Kadang mengalami gangguan', 'Sering mengalami gangguan']">
                                <label class="flex items-center gap-2 p-2.5 rounded-lg border border-gray-200 hover:bg-slate-50 cursor-pointer">
                                    <input type="radio" name="ketersediaan_sistem" :value="item" x-model="ketersediaan_sistem" class="text-indigo-600 focus:ring-indigo-500">
                                    <span x-text="item" class="text-gray-800 font-medium"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Q18. Praktik Pencadangan (Backup) Data</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs">
                            <template x-for="item in ['Ya, dilakukan secara rutin', 'Tidak rutin', 'Tidak ada backup']">
                                <label class="flex items-center p-2.5 rounded-lg border border-gray-200 hover:bg-slate-50 cursor-pointer">
                                    <input type="radio" name="backup_praktik" :value="item" x-model="backup_praktik" class="text-indigo-600 focus:ring-indigo-500 mr-2">
                                    <span class="text-gray-700" x-text="item"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Q19. Apakah Sistem Pemda Terintegrasi dengan Sistem Lain?</label>
                        <div class="flex gap-4 text-xs">
                            <label class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 hover:bg-slate-50 cursor-pointer">
                                <input type="radio" name="integrasi_sistem" value="Ya" x-model="integrasi_sistem" class="text-indigo-600 focus:ring-indigo-500">
                                <span class="text-gray-700 font-medium">Ya</span>
                            </label>
                            <label class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 hover:bg-slate-50 cursor-pointer">
                                <input type="radio" name="integrasi_sistem" value="Tidak" x-model="integrasi_sistem" class="text-indigo-600 focus:ring-indigo-500">
                                <span class="text-gray-700 font-medium">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Q20. Ketergantungan Pengelolaan Infrastruktur pada Vendor / Pihak Ke-3</label>
                        <div class="flex gap-4 text-xs">
                            <template x-for="item in ['Ya', 'Sebagian', 'Tidak']">
                                <label class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 hover:bg-slate-50 cursor-pointer">
                                    <input type="radio" name="vendor_dependency" :value="item" x-model="vendor_dependency" class="text-indigo-600 focus:ring-indigo-500">
                                    <span x-text="item" class="text-gray-700"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Q21. Kendala Utama yang Dihadapi dalam Mengelola Infrastruktur</label>
                        <textarea name="kendala_infrastruktur" x-model="kendala_infrastruktur" rows="3" placeholder="Uraikan kendala teknis server, jaringan, SDM, atau anggaran..."
                                  class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-indigo-500 bg-white"></textarea>
                    </div>
                </div>

                <div x-show="step === 2 && peran_mitra === 'Pengelola Keamanan Data'" class="space-y-4">
                    <div class="border-b border-gray-200 pb-3">
                        <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded-full font-bold">2</span>
                            Pengelola Keamanan Data - Tata Kelola (Q22 - Q28)
                        </h4>
                        <p class="text-xs text-gray-500 mt-0.5">Aspek tata kelola keamanan informasi, kontrol hak akses, audit logging, dan tim insiden siber.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Q22. Kebijakan Internal Terkait Keamanan Data / Sistem Informasi <span class="text-red-500">*</span></label>
                        <div class="space-y-2 text-xs">
                            <template x-for="item in ['Ya, tertulis dan berlaku', 'Dalam proses penyusunan', 'Belum ada']">
                                <label class="flex items-center gap-2 p-2.5 rounded-lg border border-gray-200 hover:bg-slate-50 cursor-pointer">
                                    <input type="radio" name="kebijakan_keamanan" :value="item" x-model="kebijakan_keamanan" class="text-indigo-600 focus:ring-indigo-500">
                                    <span x-text="item" class="text-gray-700 font-medium"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Q23. Klasifikasi Data Sesuai Tingkat Akses / Sensitivitas Data</label>
                        <div class="flex gap-4 text-xs">
                            <template x-for="item in ['Ya', 'Sebagian', 'Tidak']">
                                <label class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 hover:bg-slate-50 cursor-pointer">
                                    <input type="radio" name="klasifikasi_data" :value="item" x-model="klasifikasi_data" class="text-indigo-600 focus:ring-indigo-500">
                                    <span x-text="item" class="text-gray-700"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Q24. Pengaturan Hak Akses Terhadap Data</label>
                        <div class="space-y-2 text-xs">
                            <template x-for="item in ['Berdasarkan peran/jabatan', 'Berdasarkan unit kerja', 'Tidak ada pembatasan khusus']">
                                <label class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 hover:bg-slate-50 cursor-pointer">
                                    <input type="radio" name="hak_akses" :value="item" x-model="hak_akses" class="text-indigo-600 focus:ring-indigo-500">
                                    <span x-text="item" class="text-gray-700"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Q25. Pencatatan Audit Log / Logging Akses Sistem</label>
                        <div class="flex gap-4 text-xs">
                            <template x-for="item in ['Ya', 'Sebagian', 'Tidak']">
                                <label class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 hover:bg-slate-50 cursor-pointer">
                                    <input type="radio" name="logging_access" :value="item" x-model="logging_access" class="text-indigo-600 focus:ring-indigo-500">
                                    <span x-text="item" class="text-gray-700"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Q26. Tim Khusus Keamanan Data / CSIRT</label>
                        <div class="flex gap-4 text-xs">
                            <template x-for="item in ['Ya', 'Tidak']">
                                <label class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 hover:bg-slate-50 cursor-pointer">
                                    <input type="radio" name="csirt_team" :value="item" x-model="csirt_team" class="text-indigo-600 focus:ring-indigo-500">
                                    <span x-text="item" class="text-gray-700"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Q27. Prosedur atau SOP Penanganan Insiden Keamanan Data</label>
                        <div class="flex gap-4 text-xs">
                            <template x-for="item in ['Ya', 'Tidak']">
                                <label class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 hover:bg-slate-50 cursor-pointer">
                                    <input type="radio" name="sop_insiden" :value="item" x-model="sop_insiden" class="text-indigo-600 focus:ring-indigo-500">
                                    <span x-text="item" class="text-gray-700"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Q28. Deskripsi Ringkas Kendala / Insiden Keamanan Data yang Pernah Dihadapi</label>
                        <textarea name="kendala_insiden" x-model="kendala_insiden" rows="3" placeholder="Sebutkan insiden keamanan atau isi '-' jika tidak pernah terjadi insiden..."
                                  class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-indigo-500 bg-white"></textarea>
                    </div>
                </div>

                <div x-show="step === 3" class="space-y-4">
                    <div class="border-b border-gray-200 pb-3">
                        <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded-full font-bold">3</span>
                            Evaluasi Singkat & Masukan (Q29 - Q30)
                        </h4>
                        <p class="text-xs text-gray-500 mt-0.5">Penilaian kebermanfaatan kerjasama serta masukan saran pengembangan.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Q29. Rating Tingkat Manfaat & Kepuasan Kerja Sama <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-2 p-4 bg-slate-50 border border-gray-200 rounded-xl justify-center">
                            <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                <button type="button" @click="rating_manfaat = star" class="p-2 transition-transform hover:scale-125 focus:outline-none">
                                    <svg class="w-8 h-8" :class="rating_manfaat >= star ? 'text-amber-400 fill-current' : 'text-gray-300 fill-current'" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                </button>
                            </template>
                            <input type="hidden" name="rating_manfaat" :value="rating_manfaat">
                        </div>
                        <p class="text-center text-xs font-semibold text-gray-600 mt-2">
                            Skor: <span x-text="rating_manfaat"></span> / 5 
                            (<span x-text="['Tidak Bermanfaat', 'Kurang Bermanfaat', 'Cukup Bermanfaat', 'Bermanfaat', 'Sangat Bermanfaat'][rating_manfaat - 1]"></span>)
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Q30. Masukan atau Rekomendasi Perbaikan Kerja Sama Bagi Pakai Data</label>
                        <textarea name="masukan_rekomendasi" x-model="masukan_rekomendasi" rows="4" placeholder="Tuliskan saran atau masukan untuk peningkatan layanan, kualitas data, atau mekanisme kerja sama..."
                                  class="w-full border border-gray-300 rounded-lg p-3 text-xs focus:ring-2 focus:ring-indigo-500 bg-white"></textarea>
                    </div>
                </div>

                <div x-show="step === 4" class="space-y-4">
                    <div class="border-b border-gray-200 pb-3">
                        <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded-full font-bold">4</span>
                            Pernyataan Penutup & Ringkasan Submit (Q31)
                        </h4>
                        <p class="text-xs text-gray-500 mt-0.5">Periksa kembali ringkasan isian Anda sebelum mengirimkan laporan.</p>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3 text-xs">
                        <h5 class="font-bold text-gray-900 border-b border-slate-200 pb-2">Ringkasan Isian Form Laporan Tengah Tahun</h5>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <span class="text-gray-500 block text-[11px]">Nama Pemda / Instansi:</span>
                                <strong class="text-gray-800" x-text="nama_pemda || '-'"></strong>
                            </div>
                            <div>
                                <span class="text-gray-500 block text-[11px]">PIC & Kontak:</span>
                                <strong class="text-gray-800" x-text="`${nama_pic || '-'} (${kontak_pic || '-'})`"></strong>
                            </div>
                            <div>
                                <span class="text-gray-500 block text-[11px]">Peran Pelapor:</span>
                                <strong class="text-indigo-700 font-bold" x-text="peran_mitra || 'Belum dipilih'"></strong>
                            </div>
                            <div>
                                <span class="text-gray-500 block text-[11px]">Rating Kepuasan / Manfaat:</span>
                                <strong class="text-indigo-600 font-bold" x-text="`${rating_manfaat} / 5 Rating`"></strong>
                            </div>
                        </div>
                    </div>

                    <div class="bg-amber-50/80 p-4 rounded-xl border border-amber-200">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="pernyataan_kebenaran" value="1" x-model="pernyataan_kebenaran" required
                                   class="mt-1 rounded border-amber-400 text-indigo-600 focus:ring-indigo-500">
                            <div class="text-xs text-amber-900">
                                <strong class="font-bold block mb-0.5">Q31. Pernyataan Kebenaran Data <span class="text-red-500">*</span></strong>
                                <span>Saya menyatakan bahwa informasi yang disampaikan adalah benar dan digunakan untuk keperluan Pemantauan dan Evaluasi pelaksanaan kerja sama Bagi Pakai Data.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div>
                        <button type="button" x-show="step > 1" @click="prevStep()"
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 shadow-2xs transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Sebelumnya
                        </button>
                        <button type="button" x-show="step === 1" @click="showModalTengah = false"
                                class="px-4 py-2 rounded-lg text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                    </div>

                    <div>
                        <button type="button" x-show="step < 4" @click="nextStep()"
                                class="inline-flex items-center gap-1.5 px-5 py-2 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-2xs transition-colors">
                            Selanjutnya
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <button type="submit" x-show="step === 4" :disabled="!pernyataan_kebenaran"
                                :class="pernyataan_kebenaran ? 'bg-green-600 hover:bg-green-700 text-white cursor-pointer' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg text-xs font-bold shadow-xs transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Kirim Laporan Tengah Tahun
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div x-show="showModalAkhir" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(15, 23, 42, 0.6)" @keydown.escape.window="showModalAkhir = false" @click.self="showModalAkhir = false">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-slate-50">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <h3 class="text-sm font-bold text-gray-900">Upload Dokumen Laporan Akhir Tahun (<span x-text="activeTahun"></span>)</h3>
                </div>
                <button type="button" @click="showModalAkhir = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('mitra.kerjasama.laporan.store', $ks->kerjasama_id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="tahun" :value="activeTahun">
                <input type="hidden" name="periode" value="Akhir Tahun">

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">File Dokumen Laporan Akhir Tahun <span class="text-red-500">*</span></label>
                    <input type="file" name="file_laporan" accept=".pdf" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500 bg-white text-gray-700">
                    <p class="text-[11px] text-gray-400 mt-1">Format file wajib PDF (Maksimal 20MB)</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan / Ringkasan Laporan (Opsional)</label>
                    <textarea name="catatan" rows="2" placeholder="Catatan tambahan mengenai laporan akhir tahun..."
                              class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-indigo-500 bg-white"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showModalAkhir = false" 
                            class="px-4 py-2 rounded-lg text-xs font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-5 py-2 rounded-lg text-xs font-semibold text-white bg-slate-800 hover:bg-slate-900 transition-colors shadow-xs flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Unggah Laporan Akhir Tahun
                    </button>
                </div>
            </form>
        </div>
    </div>

    <x-laporan-detail-modal />

</div>
@endsection
