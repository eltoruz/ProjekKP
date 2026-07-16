<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;
use App\Services\WorkflowService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Kerjasama::notDeleted()->with(['jenis', 'tingkat']);
        if ($request->status) $query->where('status_pengajuan', $request->status);
        $kerjasamas = $query->orderBy('last_update', 'desc')->paginate(15);
        $needReview = Kerjasama::notDeleted()->byStatus('DIAJUKAN')->count();
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
        $wf = new WorkflowService;
        $ks->addReviewEntry('DISETUJUI', 'Disetujui oleh Admin');
        $wf->transition($ks, 'DISETUJUI');
        return back()->with('success', 'Disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['alasan' => 'required|string']);
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $ks->addReviewEntry('DITOLAK', $request->alasan, $request->catatan_perbaikan);
        (new WorkflowService)->transition($ks, 'DITOLAK');
        return back()->with('success', 'Ditolak.');
    }
}
