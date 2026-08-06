<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $allValid = Kerjasama::notDeleted()->where(function ($q) {
            $q->whereNotNull('ks_status_dok')->orWhereHas('reviewLogs');
        });

        $statusCounts = Kerjasama::notDeleted()
            ->selectRaw('ks_status_dok, count(*) as total')
            ->groupBy('ks_status_dok')
            ->pluck('total', 'ks_status_dok');

        $stats = [
            'total' => (clone $allValid)->count(),
            'perlu_review' => $statusCounts[1] ?? 0,
            'dalam_pembahasan' => $statusCounts[2] ?? 0,
            'dalam_proses' => ($statusCounts[3] ?? 0) + ($statusCounts[4] ?? 0),
            'selesai' => $statusCounts[5] ?? 0,
            'berakhir' => $statusCounts[6] ?? 0,
        ];

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
                $badgeText = "H-{$daysLeft} Hari";

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
                    'badge_text' => $badgeText,
                    'tanggal_selesai' => $item->tanggal_selesai_ks->format('d M Y'),
                ];
            });

        $statusDistribution = [
            'Draf' => $statusCounts[1] ?? 0,
            'Pembahasan' => $statusCounts[2] ?? 0,
            'Undangan' => $statusCounts[3] ?? 0,
            'Finalisasi' => $statusCounts[4] ?? 0,
            'Selesai' => $statusCounts[5] ?? 0,
        ];

        $recentSubmissions = (clone $allValid)
            ->with(['jenis', 'statusDok'])
            ->orderBy('last_update', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentSubmissions', 'earlyWarningList', 'statusDistribution'));
    }
}
