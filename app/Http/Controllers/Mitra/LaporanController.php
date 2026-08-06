<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;
use App\Models\MitraReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function daftar(Request $request)
    {
        $query = Kerjasama::notDeleted()
            ->where('ks_status_dok', '>=', 5)
            ->with(['jenis', 'implementasi', 'reports', 'pemilihanData'])
            ->orderBy('last_update', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kl', 'like', "%{$search}%")
                  ->orWhere('tentang', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate(15)->withQueryString();

        return view('mitra.pelaporan.index', compact('items'));
    }

    public function index($id)
    {
        $ks = Kerjasama::with(['reports', 'pemilihanData.metadata', 'implementasi'])
            ->where('kerjasama_id', $id)->notDeleted()->firstOrFail();

        $isReportingActive = $ks->is_reporting_active;
        $approvedItems = $ks->pemilihanData->where('approval_status', 'approved');
        $periodeList = $this->susunPeriodeLaporan($ks);

        return view('mitra.kerjasama.laporan', compact('ks', 'isReportingActive', 'approvedItems', 'periodeList'));
    }

    private function susunPeriodeLaporan(Kerjasama $ks): array
    {
        $jangkaWaktu = (int) $ks->jangka_waktu_thn;

        if ($jangkaWaktu < 1 && $ks->tanggal_mulai_ks && $ks->tanggal_selesai_ks) {
            $jangkaWaktu = max(1, (int) round($ks->tanggal_mulai_ks->diffInDays($ks->tanggal_selesai_ks) / 365));
        }

        if ($jangkaWaktu < 1 || !$ks->tanggal_mulai_ks) {
            return [];
        }

        $laporanTerunggah = $ks->reports->keyBy(fn ($rep) => $rep->tahun . '|' . $rep->periode);

        $tahunMulai = (int) $ks->tanggal_mulai_ks->year;
        $periodeDefinitions = [
            'Tengah Tahun' => ['rentang' => 'Januari - Juni'],
            'Akhir Tahun' => ['rentang' => 'Juli - Desember'],
        ];

        $rolesList = ['Pengelola Data', 'Pengelola Infrastruktur', 'Pengelola Keamanan Data'];
        $tahunCards = [];

        for ($i = 0; $i < $jangkaWaktu; $i++) {
            $tahun = $tahunMulai + $i;
            $tahunIndex = $i + 1;

            $periodes = [];
            foreach ($periodeDefinitions as $periodeName => $info) {
                $allPeriodReports = $ks->reports->filter(function ($rep) use ($tahun, $periodeName) {
                    if ((int)$rep->tahun !== (int)$tahun) return false;
                    if ($periodeName === 'Tengah Tahun') {
                        return in_array($rep->periode, ['Tengah Tahun', 'Semester 1']);
                    }
                    return in_array($rep->periode, ['Akhir Tahun', 'Semester 2']);
                });

                $roleReports = [];
                $completedRolesCount = 0;

                foreach ($rolesList as $role) {
                    $rep = $allPeriodReports->first(function ($r) use ($role) {
                        if (!$r->catatan) return false;
                        $d = json_decode($r->catatan, true);
                        return isset($d['identitas']['peran_mitra']) && $d['identitas']['peran_mitra'] === $role;
                    });

                    if ($rep) {
                        $completedRolesCount++;
                    }

                    $roleReports[$role] = $rep;
                }

                $status = ($completedRolesCount >= 3) ? 'terkirim' : (($completedRolesCount > 0) ? 'parsial' : 'belum');

                $periodes[$periodeName] = [
                    'nama' => $periodeName,
                    'rentang' => $info['rentang'],
                    'laporan' => $allPeriodReports->first(),
                    'role_reports' => $roleReports,
                    'completed_roles_count' => $completedRolesCount,
                    'status' => $status,
                ];
            }

            $tahunCards[] = [
                'tahun_ke' => $tahunIndex,
                'tahun' => $tahun,
                'periodes' => $periodes,
            ];
        }

        return $tahunCards;
    }

    public function store(Request $request, $id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();

        if (!$ks->is_reporting_active) {
            return back()->with('error', 'Gagal: Fitur pelaporan berkala belum aktif. Syarat: Minimal 1 item data disetujui Admin dan Status Implementasi diset Aktif.');
        }

        $validated = $request->validate([
            'tahun' => 'required|integer|min:2020|max:2099',
            'periode' => 'required|string|in:Tengah Tahun,Akhir Tahun,Semester 1,Semester 2',
            'file_laporan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:20480',
            'file_disposisi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',

            'nama_pemda' => 'nullable|string|max:255',
            'jenis_pemda' => 'nullable|string|in:Provinsi,Kabupaten,Kota',
            'unit_kerja' => 'nullable|string|max:255',
            'nama_pic' => 'nullable|string|max:255',
            'jabatan_pic' => 'nullable|string|max:255',
            'kontak_pic' => 'nullable|string|max:100',
            'email_pic' => 'nullable|email|max:255',
            'peran_mitra' => 'nullable|string',

            'program_kegiatan' => 'nullable|string',
            'tujuan_pemanfaatan' => 'nullable|array',
            'jenis_data' => 'nullable|array',
            'bentuk_pemanfaatan' => 'nullable|array',
            'publikasi_umum' => 'nullable|string',
            'media_publikasi' => 'nullable|string',

            'lokasi_pengolahan' => 'nullable|array',
            'ketersediaan_sistem' => 'nullable|string',
            'backup_praktik' => 'nullable|string',
            'integrasi_sistem' => 'nullable|string',
            'vendor_dependency' => 'nullable|string',
            'kendala_infrastruktur' => 'nullable|string',

            'kebijakan_keamanan' => 'nullable|string',
            'klasifikasi_data' => 'nullable|string',
            'hak_akses' => 'nullable|string',
            'logging_access' => 'nullable|string',
            'csirt_team' => 'nullable|string',
            'sop_insiden' => 'nullable|string',
            'kendala_insiden' => 'nullable|string',

            'rating_manfaat' => 'nullable|integer|min:1|max:5',
            'masukan_rekomendasi' => 'nullable|string',
            'pernyataan_kebenaran' => 'nullable|accepted',
            'catatan' => 'nullable|string',
        ], [
            'file_laporan.mimes' => 'Format file yang diizinkan: PDF, JPG, JPEG, PNG.',
            'file_laporan.max' => 'Ukuran file laporan maksimal 20MB.',
            'file_disposisi.mimes' => 'Format file disposisi yang diizinkan: PDF, JPG, JPEG, PNG.',
            'file_disposisi.max' => 'Ukuran file disposisi maksimal 10MB.',
            'tahun.required' => 'Tahun laporan wajib dipilih.',
            'periode.required' => 'Periode laporan wajib dipilih.',
        ]);

        $formattedCatatan = null;

        if (in_array($validated['periode'], ['Tengah Tahun', 'Semester 1']) && isset($validated['nama_pemda'])) {
            $questionnaireData = [
                'is_questionnaire' => true,
                'identitas' => [
                    'nama_pemda' => $validated['nama_pemda'] ?? null,
                    'jenis_pemda' => $validated['jenis_pemda'] ?? null,
                    'unit_kerja' => $validated['unit_kerja'] ?? null,
                    'nama_pic' => $validated['nama_pic'] ?? null,
                    'jabatan_pic' => $validated['jabatan_pic'] ?? null,
                    'kontak_pic' => $validated['kontak_pic'] ?? null,
                    'email_pic' => $validated['email_pic'] ?? null,
                    'peran_mitra' => $validated['peran_mitra'] ?? null,
                ],
                'pemanfaatan' => [
                    'program_kegiatan' => $validated['program_kegiatan'] ?? null,
                    'tujuan_pemanfaatan' => $validated['tujuan_pemanfaatan'] ?? [],
                    'jenis_data' => $validated['jenis_data'] ?? [],
                    'bentuk_pemanfaatan' => $validated['bentuk_pemanfaatan'] ?? [],
                    'publikasi_umum' => $validated['publikasi_umum'] ?? null,
                    'media_publikasi' => $validated['media_publikasi'] ?? null,
                ],
                'infrastruktur' => [
                    'lokasi_pengolahan' => $validated['lokasi_pengolahan'] ?? [],
                    'ketersediaan_sistem' => $validated['ketersediaan_sistem'] ?? null,
                    'backup_praktik' => $validated['backup_praktik'] ?? null,
                    'integrasi_sistem' => $validated['integrasi_sistem'] ?? null,
                    'vendor_dependency' => $validated['vendor_dependency'] ?? null,
                    'kendala_infrastruktur' => $validated['kendala_infrastruktur'] ?? null,
                ],
                'keamanan' => [
                    'kebijakan_keamanan' => $validated['kebijakan_keamanan'] ?? null,
                    'klasifikasi_data' => $validated['klasifikasi_data'] ?? null,
                    'hak_akses' => $validated['hak_akses'] ?? null,
                    'logging_access' => $validated['logging_access'] ?? null,
                    'csirt_team' => $validated['csirt_team'] ?? null,
                    'sop_insiden' => $validated['sop_insiden'] ?? null,
                    'kendala_insiden' => $validated['kendala_insiden'] ?? null,
                ],
                'evaluasi' => [
                    'rating_manfaat' => $validated['rating_manfaat'] ?? null,
                    'masukan_rekomendasi' => $validated['masukan_rekomendasi'] ?? null,
                ],
            ];
            $formattedCatatan = json_encode($questionnaireData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            $catatanParts = [];
            if (!empty($request->input('peran1'))) {
                $catatanParts[] = "Peran 1: " . trim($request->input('peran1'));
            }
            if (!empty($request->input('peran2'))) {
                $catatanParts[] = "Peran 2: " . trim($request->input('peran2'));
            }
            if (!empty($request->input('peran3'))) {
                $catatanParts[] = "Peran 3: " . trim($request->input('peran3'));
            }
            if (!empty($validated['catatan'])) {
                $catatanParts[] = trim($validated['catatan']);
            }
            $formattedCatatan = !empty($catatanParts) ? implode("\n\n", $catatanParts) : null;
        }

        $peran = $validated['peran_mitra'] ?? null;
        $fileNameSuffix = $peran ? " ({$peran})" : "";
        $defaultFileName = 'Laporan ' . $validated['periode'] . $fileNameSuffix;

        $filePath = null;
        $originalName = null;

        if ($request->hasFile('file_laporan')) {
            $filePath = $request->file('file_laporan')->store('laporan/' . $id, 'public');
            $originalName = $request->file('file_laporan')->getClientOriginalName();
        } elseif ($request->hasFile('file_disposisi')) {
            $filePath = $request->file('file_disposisi')->store('laporan/' . $id . '/disposisi', 'public');
            $originalName = $request->file('file_disposisi')->getClientOriginalName();
        }

        $queryExisting = MitraReport::where('kerjasama_id', $id)
            ->where('tahun', $validated['tahun'])
            ->where('periode', $validated['periode']);

        if ($peran) {
            $queryExisting->where(function ($q) use ($peran) {
                $q->where('catatan', 'like', '%"peran_mitra":"' . $peran . '"%')
                  ->orWhere('catatan', 'like', '%"peran_mitra": "' . $peran . '"%');
            });
        }

        $existingReport = $queryExisting->first();

        if ($existingReport) {
            $updateData = [
                'catatan' => $formattedCatatan ?? $existingReport->catatan,
                'status' => 'dikirim',
            ];

            if ($filePath) {
                if ($existingReport->file_path && Storage::disk('public')->exists($existingReport->file_path)) {
                    Storage::disk('public')->delete($existingReport->file_path);
                }
                $updateData['file_path'] = $filePath;
                $updateData['nama_file'] = $originalName ?? $defaultFileName;
            }

            $existingReport->update($updateData);
        } else {
            MitraReport::create([
                'kerjasama_id' => $id,
                'tahun' => $validated['tahun'],
                'periode' => $validated['periode'],
                'file_path' => $filePath ?? '',
                'nama_file' => $originalName ?? $defaultFileName,
                'catatan' => $formattedCatatan,
                'status' => 'dikirim',
            ]);
        }

        $peranText = $peran ? " ({$peran})" : "";
        $ks->addReviewEntry('Laporan Berkala', "Mitra mengisi/mengunggah laporan berkala {$validated['periode']} {$validated['tahun']}{$peranText}");

        return redirect()->route('mitra.kerjasama.laporan', $id)
            ->with('success', "Laporan berkala {$validated['periode']} {$validated['tahun']}{$peranText} berhasil dikirim.");
    }
}
