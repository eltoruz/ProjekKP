<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JadwalPembahasanController extends Controller
{
    /**
     * Kalender bulanan jadwal pembahasan kerja sama.
     *
     * Event diambil dari kerja sama berstatus 3 (Dokumen dalam proses pembahasan),
     * yaitu tahap setelah mitra mengunggah surat undangan jadwal pembahasan.
     */
    public function index(Request $request)
    {
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        // Jaga agar bulan/tahun di luar rentang tidak membuat Carbon melempar error
        if ($bulan < 1 || $bulan > 12) {
            $bulan = now()->month;
        }
        if ($tahun < 2000 || $tahun > 2100) {
            $tahun = now()->year;
        }

        $awalBulan = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $akhirBulan = (clone $awalBulan)->endOfMonth();

        $jadwalList = Kerjasama::notDeleted()
            ->byStatus(3)
            ->whereNotNull('tanggal_pembahasan')
            ->whereBetween('tanggal_pembahasan', [$awalBulan, $akhirBulan])
            ->with(['jenis', 'tingkat', 'statusDok'])
            ->orderBy('tanggal_pembahasan')
            ->get();

        // Kelompokkan per tanggal agar mudah dipetakan ke sel kalender
        $eventsByDate = $jadwalList->groupBy(fn ($ks) => $ks->tanggal_pembahasan->format('Y-m-d'));

        // Grid kalender dimulai hari Senin, mencakup tanggal menggantung bulan sebelum/sesudah
        $mulaiGrid = (clone $awalBulan)->startOfWeek(Carbon::MONDAY);
        $selesaiGrid = (clone $akhirBulan)->endOfWeek(Carbon::SUNDAY);

        $hariList = [];
        for ($tgl = clone $mulaiGrid; $tgl <= $selesaiGrid; $tgl->addDay()) {
            $iso = $tgl->format('Y-m-d');
            $hariList[] = [
                'tanggal' => $tgl->copy(),
                'iso' => $iso,
                'is_bulan_ini' => $tgl->month === $awalBulan->month,
                'is_hari_ini' => $tgl->isToday(),
                'events' => $eventsByDate->get($iso, collect()),
            ];
        }

        $mingguList = array_chunk($hariList, 7);

        $bulanSebelumnya = (clone $awalBulan)->subMonth();
        $bulanBerikutnya = (clone $awalBulan)->addMonth();

        return view('admin.jadwal-pembahasan.index', compact(
            'jadwalList',
            'mingguList',
            'awalBulan',
            'bulan',
            'tahun',
            'bulanSebelumnya',
            'bulanBerikutnya'
        ));
    }
}
