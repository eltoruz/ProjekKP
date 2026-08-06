<div x-show="showDetailModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center z-50 p-4"
     style="display: none;">

    <div x-show="showDetailModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.away="showDetailModal = false"
         class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] flex flex-col shadow-2xl overflow-hidden border border-gray-100">

        <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between border-b border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-600/30 border border-indigo-500/30 flex items-center justify-center text-indigo-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg font-bold text-white">Detail Kuesioner Laporan Tengah Tahun</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30" x-text="detailPeriodeTahun"></span>
                    </div>
                    <p class="text-xs text-slate-400">Form Evaluasi & Governance Pemanfaatan Data Mitra</p>
                </div>
            </div>
            <button @click="showDetailModal = false" type="button" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50/50">

            <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-2xs space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                    <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs">1</div>
                    <h4 class="font-bold text-gray-900 text-sm">Identitas Pelapor & Penanggung Jawab</h4>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
                    <div>
                        <span class="text-gray-500 font-medium block mb-0.5">Nama Pemda / Instansi:</span>
                        <span class="font-semibold text-gray-800" x-text="detailData?.identitas?.nama_pemda || '-'"></span>
                    </div>
                    <div>
                        <span class="text-gray-500 font-medium block mb-0.5">Jenis Pemda:</span>
                        <span class="font-semibold text-gray-800" x-text="detailData?.identitas?.jenis_pemda || '-'"></span>
                    </div>
                    <div>
                        <span class="text-gray-500 font-medium block mb-0.5">Unit Kerja / Perangkat Daerah:</span>
                        <span class="font-semibold text-gray-800" x-text="detailData?.identitas?.unit_kerja || '-'"></span>
                    </div>
                    <div>
                        <span class="text-gray-500 font-medium block mb-0.5">Nama PIC / Penanggung Jawab:</span>
                        <span class="font-semibold text-gray-800" x-text="detailData?.identitas?.nama_pic || '-'"></span>
                    </div>
                    <div>
                        <span class="text-gray-500 font-medium block mb-0.5">Jabatan PIC:</span>
                        <span class="font-semibold text-gray-800" x-text="detailData?.identitas?.jabatan_pic || '-'"></span>
                    </div>
                    <div>
                        <span class="text-gray-500 font-medium block mb-0.5">No. Kontak / WA PIC:</span>
                        <span class="font-semibold text-gray-800" x-text="detailData?.identitas?.kontak_pic || '-'"></span>
                    </div>
                    <div>
                        <span class="text-gray-500 font-medium block mb-0.5">Email Resmi PIC:</span>
                        <span class="font-semibold text-gray-800" x-text="detailData?.identitas?.email_pic || '-'"></span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="text-gray-500 font-medium block mb-0.5">Peran Mitra Dalam Pemanfaatan Data:</span>
                        <span class="font-semibold text-gray-800" x-text="detailData?.identitas?.peran_mitra || '-'"></span>
                    </div>
                </div>

                <template x-if="detailFilePath">
                    <div class="mt-3 p-3 bg-indigo-50/70 border border-indigo-200 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-indigo-100 text-indigo-700 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-900">Surat Disposisi / Lampiran Laporan</p>
                                <p class="text-[11px] text-gray-500">File dokumen pendukung yang diunggah saat pengisian kuesioner</p>
                            </div>
                        </div>
                        <a :href="detailFilePath" target="_blank" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-colors shadow-2xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Unduh Surat Disposisi
                        </a>
                    </div>
                </template>
            </div>

            <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-2xs space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs">2</div>
                    <h4 class="font-bold text-gray-900 text-sm">Pengelola Data - Pemanfaatan & Publikasi</h4>
                </div>

                <div class="space-y-3.5 text-xs">
                    <div>
                        <span class="text-gray-500 font-medium block mb-1">Program / Kegiatan Pemanfaatan Data:</span>
                        <p class="text-gray-800 bg-gray-50 p-2.5 rounded-lg border border-gray-100 whitespace-pre-line leading-relaxed" x-text="detailData?.pemanfaatan?.program_kegiatan || '-'"></p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-500 font-medium block mb-1.5">Tujuan Pemanfaatan Data:</span>
                            <template x-if="Array.isArray(detailData?.pemanfaatan?.tujuan_pemanfaatan) && detailData.pemanfaatan.tujuan_pemanfaatan.length > 0">
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="item in detailData.pemanfaatan.tujuan_pemanfaatan" :key="item">
                                        <span class="px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200" x-text="item"></span>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!Array.isArray(detailData?.pemanfaatan?.tujuan_pemanfaatan) || detailData.pemanfaatan.tujuan_pemanfaatan.length === 0">
                                <span class="text-gray-700" x-text="detailData?.pemanfaatan?.tujuan_pemanfaatan || '-'"></span>
                            </template>
                        </div>

                        <div>
                            <span class="text-gray-500 font-medium block mb-1.5">Jenis Data yang Dimanfaatkan:</span>
                            <template x-if="Array.isArray(detailData?.pemanfaatan?.jenis_data) && detailData.pemanfaatan.jenis_data.length > 0">
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="item in detailData.pemanfaatan.jenis_data" :key="item">
                                        <span class="px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200" x-text="item"></span>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!Array.isArray(detailData?.pemanfaatan?.jenis_data) || detailData.pemanfaatan.jenis_data.length === 0">
                                <span class="text-gray-700" x-text="detailData?.pemanfaatan?.jenis_data || '-'"></span>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
                        <div>
                            <span class="text-gray-500 font-medium block mb-1.5">Bentuk Pemanfaatan Data:</span>
                            <template x-if="Array.isArray(detailData?.pemanfaatan?.bentuk_pemanfaatan) && detailData.pemanfaatan.bentuk_pemanfaatan.length > 0">
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="item in detailData.pemanfaatan.bentuk_pemanfaatan" :key="item">
                                        <span class="px-2.5 py-1 rounded-md text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200" x-text="item"></span>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!Array.isArray(detailData?.pemanfaatan?.bentuk_pemanfaatan) || detailData.pemanfaatan.bentuk_pemanfaatan.length === 0">
                                <span class="text-gray-700" x-text="detailData?.pemanfaatan?.bentuk_pemanfaatan || '-'"></span>
                            </template>
                        </div>

                        <div>
                            <span class="text-gray-500 font-medium block mb-0.5">Dipublikasikan ke Publik/Masyarakat:</span>
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                  :class="detailData?.pemanfaatan?.publikasi_umum === 'Ya' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700'"
                                  x-text="detailData?.pemanfaatan?.publikasi_umum || '-'"></span>
                        </div>

                        <div>
                            <span class="text-gray-500 font-medium block mb-0.5">Media Publikasi:</span>
                            <span class="font-semibold text-gray-800" x-text="detailData?.pemanfaatan?.media_publikasi || '-'"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-2xs space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                    <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-xs">3</div>
                    <h4 class="font-bold text-gray-900 text-sm">Pengelola Infrastruktur - Kondisi Pendukung</h4>
                </div>

                <div class="space-y-3.5 text-xs">
                    <div>
                        <span class="text-gray-500 font-medium block mb-1.5">Lokasi Pengolahan & Penyimpanan Data:</span>
                        <template x-if="Array.isArray(detailData?.infrastruktur?.lokasi_pengolahan) && detailData.infrastruktur.lokasi_pengolahan.length > 0">
                            <div class="flex flex-wrap gap-1.5">
                                <template x-for="item in detailData.infrastruktur.lokasi_pengolahan" :key="item">
                                    <span class="px-2.5 py-1 rounded-md text-xs font-medium bg-teal-50 text-teal-700 border border-teal-200" x-text="item"></span>
                                </template>
                            </div>
                        </template>
                        <template x-if="!Array.isArray(detailData?.infrastruktur?.lokasi_pengolahan) || detailData.infrastruktur.lokasi_pengolahan.length === 0">
                            <span class="text-gray-700" x-text="detailData?.infrastruktur?.lokasi_pengolahan || '-'"></span>
                        </template>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-500 font-medium block mb-0.5">Ketersediaan Sistem Data:</span>
                            <span class="font-semibold text-gray-800" x-text="detailData?.infrastruktur?.ketersediaan_sistem || '-'"></span>
                        </div>
                        <div>
                            <span class="text-gray-500 font-medium block mb-0.5">Prosedur & Frekuensi Backup Data:</span>
                            <span class="font-semibold text-gray-800" x-text="detailData?.infrastruktur?.backup_praktik || '-'"></span>
                        </div>
                        <div>
                            <span class="text-gray-500 font-medium block mb-0.5">Tingkat Integrasi Sistem:</span>
                            <span class="font-semibold text-gray-800" x-text="detailData?.infrastruktur?.integrasi_sistem || '-'"></span>
                        </div>
                        <div>
                            <span class="text-gray-500 font-medium block mb-0.5">Ketergantungan Vendor / Pihak Ke-3:</span>
                            <span class="font-semibold text-gray-800" x-text="detailData?.infrastruktur?.vendor_dependency || '-'"></span>
                        </div>
                    </div>

                    <template x-if="detailData?.infrastruktur?.kendala_infrastruktur">
                        <div class="pt-1">
                            <span class="text-gray-500 font-medium block mb-1">Kendala Infrastruktur Yang Dihadapi:</span>
                            <p class="text-gray-800 bg-amber-50/50 p-2.5 rounded-lg border border-amber-100 whitespace-pre-line" x-text="detailData.infrastruktur.kendala_infrastruktur"></p>
                        </div>
                    </template>
                </div>
            </div>

            <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-2xs space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                    <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs">4</div>
                    <h4 class="font-bold text-gray-900 text-sm">Pengelola Keamanan Data - Tata Kelola</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-gray-500 font-medium block mb-0.5">Kebijakan Keamanan Informasi:</span>
                        <span class="font-semibold text-gray-800" x-text="detailData?.keamanan?.kebijakan_keamanan || '-'"></span>
                    </div>
                    <div>
                        <span class="text-gray-500 font-medium block mb-0.5">Klasifikasi Data:</span>
                        <span class="font-semibold text-gray-800" x-text="detailData?.keamanan?.klasifikasi_data || '-'"></span>
                    </div>
                    <div>
                        <span class="text-gray-500 font-medium block mb-0.5">Pengaturan Hak Akses Pengguna:</span>
                        <span class="font-semibold text-gray-800" x-text="detailData?.keamanan?.hak_akses || '-'"></span>
                    </div>
                    <div>
                        <span class="text-gray-500 font-medium block mb-0.5">Audit Log / Log Akses Pengguna:</span>
                        <span class="font-semibold text-gray-800" x-text="detailData?.keamanan?.logging_access || '-'"></span>
                    </div>
                    <div>
                        <span class="text-gray-500 font-medium block mb-0.5">Tim Tanggap Insiden Siber (CSIRT):</span>
                        <span class="font-semibold text-gray-800" x-text="detailData?.keamanan?.csirt_team || '-'"></span>
                    </div>
                    <div>
                        <span class="text-gray-500 font-medium block mb-0.5">SOP Penanganan Insiden Keamanan:</span>
                        <span class="font-semibold text-gray-800" x-text="detailData?.keamanan?.sop_insiden || '-'"></span>
                    </div>
                </div>

                <template x-if="detailData?.keamanan?.kendala_insiden">
                    <div class="pt-1 text-xs">
                        <span class="text-gray-500 font-medium block mb-1">Catatan Kendala Keamanan / Insiden:</span>
                        <p class="text-gray-800 bg-red-50/50 p-2.5 rounded-lg border border-red-100 whitespace-pre-line" x-text="detailData.keamanan.kendala_insiden"></p>
                    </div>
                </template>
            </div>

            <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-2xs space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">5</div>
                    <h4 class="font-bold text-gray-900 text-sm">Evaluasi Singkat & Masukan</h4>
                </div>

                <div class="space-y-4 text-xs">
                    <div>
                        <span class="text-gray-500 font-medium block mb-2">Rating Manfaat Penggunaan Data Bagi Instansi:</span>
                        <div class="flex items-center gap-1.5 p-3 bg-amber-50/40 rounded-xl border border-amber-100 w-fit">
                            <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                <svg class="w-6 h-6 transition-colors" 
                                     :class="star <= (detailData?.evaluasi?.rating_manfaat || 0) ? 'text-amber-400 fill-amber-400' : 'text-gray-200 fill-gray-100'" 
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385c.116.488-.415.87-.837.618l-4.717-2.825a.562.562 0 00-.586 0l-4.717 2.825c-.422.252-.953-.13-.837-.618l1.285-5.385a.563.563 0 00-.182-.557l-4.204-3.602c-.38-.325-.178-.948.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                </svg>
                            </template>
                            <span class="ml-2 font-bold text-amber-700 text-sm" x-text="(detailData?.evaluasi?.rating_manfaat || 0) + ' / 5 Star'"></span>
                        </div>
                    </div>

                    <div>
                        <span class="text-gray-500 font-medium block mb-1">Masukan & Rekomendasi Tambahan:</span>
                        <p class="text-gray-800 bg-gray-50 p-2.5 rounded-lg border border-gray-100 whitespace-pre-line leading-relaxed" x-text="detailData?.evaluasi?.masukan_rekomendasi || '-'"></p>
                    </div>
                </div>
            </div>

            <div class="bg-emerald-50/60 rounded-xl p-4 border border-emerald-200/80 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="text-xs">
                    <p class="font-bold text-emerald-900">Kuesioner Laporan Telah Disubmit</p>
                    <p class="text-emerald-700">Mitra telah mengonfirmasi bahwa data yang disampaikan dalam laporan ini adalah benar dan dapat dipertanggungjawabkan.</p>
                </div>
            </div>

        </div>

        <div class="px-6 py-3.5 bg-gray-50 border-t border-gray-200 flex justify-end">
            <button @click="showDetailModal = false" type="button" class="px-5 py-2 rounded-xl text-xs font-semibold text-gray-700 bg-white hover:bg-gray-100 border border-gray-300 transition-colors shadow-2xs">
                Tutup
            </button>
        </div>
    </div>
</div>
