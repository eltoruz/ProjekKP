<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;
use App\Services\WorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NotaKesepakatanController extends Controller
{
    public function upload(Request $request, $id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $request->validate([
            'surat_permohonan' => 'required|file|mimes:pdf,docx,zip|max:20480',
            'draft_nk' => 'required|file|mimes:pdf,docx,zip|max:20480',
        ]);

        $paths = [];
        if ($request->hasFile('surat_permohonan')) $paths[] = $request->file('surat_permohonan')->store('dokumen/' . $id, 'public');
        if ($request->hasFile('draft_nk')) $paths[] = $request->file('draft_nk')->store('dokumen/' . $id, 'public');

        $existing = $ks->dokumen_ks ? json_decode($ks->dokumen_ks, true) ?: [] : [];
        $ks->update(['dokumen_ks' => json_encode(array_merge($existing, $paths))]);

        (new WorkflowService)->transition($ks, 'UPLOAD_DOKUMEN');
        return redirect()->route('mitra.kerjasama.show', $id)->with('success', 'Dokumen diupload.');
    }

    public function ajukan($id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        (new WorkflowService)->transition($ks, 'DIAJUKAN');
        $ks->addReviewEntry('DIAJUKAN', 'Pengajuan baru dari mitra');
        return redirect()->route('mitra.kerjasama.show', $id)->with('success', 'Diajukan ke Admin.');
    }

    public function uploadUlangForm($id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        if ($ks->status_pengajuan !== 'DITOLAK') return redirect()->route('mitra.kerjasama.show', $id)->with('error', 'Hanya pengajuan ditolak.');
        $logs = $ks->review_log;
        $lastReject = collect($logs)->where('status', 'DITOLAK')->last();
        return view('mitra.nota-kesepakatan.upload-ulang', compact('ks', 'lastReject'));
    }

    public function uploadUlang(Request $request, $id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        if ($ks->status_pengajuan !== 'DITOLAK') return back()->with('error', 'Hanya pengajuan ditolak.');
        $request->validate([
            'surat_permohonan' => 'required|file|mimes:pdf,docx,zip|max:20480',
            'draft_nk' => 'required|file|mimes:pdf,docx,zip|max:20480',
        ]);

        $paths = [];
        if ($request->hasFile('surat_permohonan')) $paths[] = $request->file('surat_permohonan')->store('dokumen/' . $id, 'public');
        if ($request->hasFile('draft_nk')) $paths[] = $request->file('draft_nk')->store('dokumen/' . $id, 'public');
        $ks->update(['dokumen_ks' => json_encode($paths)]);

        $wf = new WorkflowService;
        $wf->transition($ks, 'UPLOAD_DOKUMEN');
        $wf->transition($ks, 'DIAJUKAN');
        $ks->addReviewEntry('UPLOAD_ULANG', 'Mitra upload ulang setelah ditolak');
        return redirect()->route('mitra.kerjasama.show', $id)->with('success', 'Dokumen diupload ulang.');
    }
}
