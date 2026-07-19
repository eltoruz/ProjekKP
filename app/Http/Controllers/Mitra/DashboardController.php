<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;

class DashboardController extends Controller
{
    public function index()
    {
        $total = Kerjasama::notDeleted()->count();
        $belum = Kerjasama::notDeleted()->whereNull('ks_status_dok')->count();
        $dibahas = Kerjasama::notDeleted()->byStatus(2)->count();
        $disetujui = Kerjasama::notDeleted()->whereIn('ks_status_dok', [3, 4])->count();
        $selesai = Kerjasama::notDeleted()->byStatus(5)->count();
        $expired = Kerjasama::notDeleted()->byStatus(6)->count();
        $upcoming = Kerjasama::notDeleted()->where('ks_status_dok', 2)->whereNotNull('tanggal_pembahasan')->orderBy('tanggal_pembahasan')->limit(5)->get();
        $recent = Kerjasama::notDeleted()->with(['jenis', 'tingkat'])->orderBy('last_update', 'desc')->limit(5)->get();

        return view('mitra.dashboard', compact('total','belum','dibahas','disetujui','selesai','expired','upcoming','recent'));
    }
}
