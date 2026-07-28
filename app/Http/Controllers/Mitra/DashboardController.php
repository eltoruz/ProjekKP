<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $statusCounts = Kerjasama::notDeleted()
            ->selectRaw('ks_status_dok, count(*) as total')
            ->groupBy('ks_status_dok')
            ->pluck('total', 'ks_status_dok');

        $total = Kerjasama::notDeleted()->count();
        $belum = Kerjasama::notDeleted()->whereNull('ks_status_dok')->count();
        $dibahas = $statusCounts[2] ?? 0;
        $disetujui = ($statusCounts[3] ?? 0) + ($statusCounts[4] ?? 0);
        $selesai = $statusCounts[5] ?? 0;
        $expired = $statusCounts[6] ?? 0;
        $upcoming = Kerjasama::notDeleted()->where('ks_status_dok', 2)->whereNotNull('tanggal_pembahasan')->orderBy('tanggal_pembahasan')->limit(5)->get();
        $recent = Kerjasama::notDeleted()->with(['jenis', 'tingkat'])->orderBy('last_update', 'desc')->limit(5)->get();

        // Early Warning MoU (H-90, H-60, H-30) for Mitra
        $now = Carbon::now();
        $target90Days = Carbon::now()->addDays(90);

        $earlyWarningList = Kerjasama::notDeleted()
            ->whereNotNull('tanggal_selesai_ks')
            ->where('tanggal_selesai_ks', '>=', $now)
            ->where('tanggal_selesai_ks', '<=', $target90Days)
            ->orderBy('tanggal_selesai_ks', 'asc')
            ->get()
            ->map(function ($item) {
                $daysLeft = (int)$item->sisa_masa_berlaku_hari;
                $level = 'warning';
                if ($daysLeft <= 30) {
                    $level = 'danger';
                } elseif ($daysLeft <= 60) {
                    $level = 'warning';
                } else {
                    $level = 'info';
                }
                return [
                    'kerjasama' => $item,
                    'days_left' => $daysLeft,
                    'level' => $level,
                    'badge_text' => "H-{$daysLeft} Hari",
                    'tanggal_selesai' => $item->tanggal_selesai_ks->format('d M Y'),
                ];
            });

        return view('mitra.dashboard', compact('total','belum','dibahas','disetujui','selesai','expired','upcoming','recent', 'earlyWarningList'));
    }
}
