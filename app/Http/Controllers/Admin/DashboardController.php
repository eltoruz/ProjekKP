<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Kerjasama::notDeleted()->where(function ($q) {
                $q->whereNotNull('ks_status_dok')->orWhereHas('reviewLogs');
            })->count(),
            'perlu_review' => Kerjasama::notDeleted()->byStatus(1)->count(),
            'dalam_pembahasan' => Kerjasama::notDeleted()->byStatus(2)->count(),
            'dalam_proses' => Kerjasama::notDeleted()->whereIn('ks_status_dok', [3, 4])->count(),
            'selesai' => Kerjasama::notDeleted()->byStatus(5)->count(),
            'berakhir' => Kerjasama::notDeleted()->byStatus(6)->count(),
        ];

        $recentSubmissions = Kerjasama::notDeleted()
            ->where(function ($q) {
                $q->whereNotNull('ks_status_dok')->orWhereHas('reviewLogs');
            })
            ->with(['jenis', 'statusDok'])
            ->orderBy('last_update', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentSubmissions'));
    }
}
