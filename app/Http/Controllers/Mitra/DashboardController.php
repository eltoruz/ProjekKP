<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;

class DashboardController extends Controller
{
    public function index()
    {
        $total = Kerjasama::notDeleted()->count();
        $draft = Kerjasama::notDeleted()->byStatus('DRAFT')->count();
        $diajukan = Kerjasama::notDeleted()->whereIn('status_pengajuan', ['DIAJUKAN','REVIEW_ADMIN'])->count();
        $ditolak = Kerjasama::notDeleted()->byStatus('DITOLAK')->count();
        $disetujui = Kerjasama::notDeleted()->whereIn('status_pengajuan', ['DISETUJUI','MENUNGGU_PEMBAHASAN','SELESAI_PEMBAHASAN','PROSES_TTD'])->count();
        $selesai = Kerjasama::notDeleted()->byStatus('SELESAI')->count();
        $expired = Kerjasama::notDeleted()->byStatus('EXPIRED')->count();
        $upcoming = Kerjasama::notDeleted()->where('status_pengajuan', 'MENUNGGU_PEMBAHASAN')->whereNotNull('tanggal_pembahasan_ks')->orderBy('tanggal_pembahasan_ks')->limit(5)->get();
        $recent = Kerjasama::notDeleted()->with(['jenis', 'tingkat'])->orderBy('last_update', 'desc')->limit(5)->get();

        return view('mitra.dashboard', compact('total','draft','diajukan','ditolak','disetujui','selesai','expired','upcoming','recent'));
    }
}
