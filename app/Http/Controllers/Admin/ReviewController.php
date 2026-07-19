<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Kerjasama::notDeleted()->with(['jenis', 'tingkat', 'statusDok']);
        if ($request->status) $query->where('ks_status_dok', $request->status);
        $kerjasamas = $query->orderBy('last_update', 'desc')->paginate(15);
        $needReview = Kerjasama::notDeleted()->byStatus(1)->count();
        return view('admin.review.index', ['kerjasamas' => $kerjasamas, 'needReview' => $needReview]);
    }

    public function show($id)
    {
        $ks = Kerjasama::with(['jenis', 'tingkat', 'statusDok', 'metode', 'implementasi'])
            ->where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        return view('admin.review.show', ['kerjasama' => $ks]);
    }

    public function approve($id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $ks->update(['ks_status_dok' => 2]);
        $ks->addReviewEntry('Disetujui', 'Disetujui oleh Admin');
        return back()->with('success', 'Disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['alasan' => 'required|string']);
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $ks->update(['ks_status_dok' => 1]);
        $ks->addReviewEntry('Ditolak', $request->alasan, $request->catatan_perbaikan);
        return back()->with('success', 'Ditolak.');
    }
}
