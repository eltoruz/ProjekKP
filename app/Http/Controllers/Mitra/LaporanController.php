<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;
use App\Models\MitraReport;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    /**
     * Susun daftar periode laporan berkala sepanjang masa berlaku kerja sama.
     *
     * Kewajiban pelaporan 2x per tahun (Tengah Tahun & Akhir Tahun), sehingga jumlah
     * periode = jangka_waktu_thn x 2, dihitung mulai dari tahun tanggal_mulai_ks.
     * Mengembalikan array kartu tahun bila jangka waktu / tanggal mulai tersedia.
     */
    private function susunPeriodeLaporan(Kerjasama $ks): array
    {
        $jangkaWaktu = (int) $ks->jangka_waktu_thn;

        if ($jangkaWaktu < 1 || !$ks->tanggal_mulai_ks) {
            return [];
        }

        // Petakan laporan terunggah dengan kunci "tahun|periode" untuk pencocokan cepat
        $laporanTerunggah = $ks->reports->keyBy(fn ($rep) => $rep->tahun . '|' . $rep->periode);

        $tahunMulai = (int) $ks->tanggal_mulai_ks->year;
        $periodeDefinitions = [
            'Tengah Tahun' => ['rentang' => 'Januari - Juni'],
            'Akhir Tahun' => ['rentang' => 'Juli - Desember'],
        ];

        $tahunCards = [];

        for ($i = 0; $i < $jangkaWaktu; $i++) {
            $tahun = $tahunMulai + $i;
            $tahunIndex = $i + 1;

            $periodes = [];
            foreach ($periodeDefinitions as $periodeName => $info) {
                $laporan = $laporanTerunggah->get($tahun . '|' . $periodeName);
                if (!$laporan && $periodeName === 'Tengah Tahun') {
                    $laporan = $laporanTerunggah->get($tahun . '|Semester 1');
                }
                if (!$laporan && $periodeName === 'Akhir Tahun') {
                    $laporan = $laporanTerunggah->get($tahun . '|Semester 2');
                }

                $periodes[$periodeName] = [
                    'nama' => $periodeName,
                    'rentang' => $info['rentang'],
                    'laporan' => $laporan,
                    'status' => $laporan ? 'terkirim' : 'belum',
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
            'file_laporan' => 'nullable|file|mimes:pdf|max:20480',
            'catatan' => 'nullable|string|max:1000',
            'peran1' => 'nullable|string',
            'peran2' => 'nullable|string',
            'peran3' => 'nullable|string',
        ], [
            'file_laporan.mimes' => 'Format file yang diizinkan hanya: PDF.',
            'file_laporan.max' => 'Ukuran file laporan maksimal 20MB.',
            'tahun.required' => 'Tahun laporan wajib dipilih.',
            'periode.required' => 'Periode laporan wajib dipilih.',
        ]);

        $filePath = null;
        $originalName = null;

        if ($request->hasFile('file_laporan')) {
            $filePath = $request->file('file_laporan')->store('laporan/' . $id, 'public');
            $originalName = $request->file('file_laporan')->getClientOriginalName();
        }

        MitraReport::create([
            'kerjasama_id' => $id,
            'tahun' => $validated['tahun'],
            'periode' => $validated['periode'],
            'file_path' => $filePath ?? '',
            'nama_file' => $originalName ?? 'Laporan ' . $validated['periode'],
            'catatan' => $validated['catatan'] ?? null,
            'status' => 'dikirim',
        ]);

        $ks->addReviewEntry('Laporan Berkala', "Mitra mengisi/mengunggah laporan berkala {$validated['periode']} {$validated['tahun']}");

        return redirect()->route('mitra.kerjasama.laporan', $id)
            ->with('success', "Laporan berkala {$validated['periode']} {$validated['tahun']} berhasil dikirim.");
    }
}
