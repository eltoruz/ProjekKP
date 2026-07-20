<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Kerjasama::notDeleted()->count(),
            'perlu_review' => Kerjasama::notDeleted()->byStatus(1)->count(),
            'dalam_pembahasan' => Kerjasama::notDeleted()->byStatus(2)->count(),
            'dalam_proses' => Kerjasama::notDeleted()->whereIn('ks_status_dok', [3, 4])->count(),
            'selesai' => Kerjasama::notDeleted()->byStatus(5)->count(),
            'berakhir' => Kerjasama::notDeleted()->byStatus(6)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
