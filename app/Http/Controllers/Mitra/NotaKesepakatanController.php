<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;
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

        $existing = $ks->folder_ks ? json_decode($ks->folder_ks, true) ?: [] : [];
        $ks->update([
            'folder_ks' => json_encode(array_merge($existing, $paths)),
        ]);

        return redirect()->route('mitra.kerjasama.show', $id)->with('success', 'Dokumen diupload.');
    }

    public function ajukan($id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $ks->update(['ks_status_dok' => 1]);
        $ks->addReviewEntry('Diajukan', 'Pengajuan baru dari mitra');
        return redirect()->route('mitra.kerjasama.show', $id)->with('success', 'Diajukan ke Admin. Silakan menunggu konfirmasi dari Admin Pusdatin.');
    }

    public function uploadUlangForm($id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $logs = $ks->review_log;
        $lastReject = collect($logs)->filter(fn($l) => $l['label'] === 'Ditolak')->last();
        return view('mitra.nota-kesepakatan.upload-ulang', compact('ks', 'lastReject'));
    }

    public function uploadUlang(Request $request, $id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $request->validate([
            'surat_permohonan' => 'required|file|mimes:pdf,docx,zip|max:20480',
            'draft_nk' => 'required|file|mimes:pdf,docx,zip|max:20480',
        ]);

        $paths = [];
        if ($request->hasFile('surat_permohonan')) $paths[] = $request->file('surat_permohonan')->store('dokumen/' . $id, 'public');
        if ($request->hasFile('draft_nk')) $paths[] = $request->file('draft_nk')->store('dokumen/' . $id, 'public');
        $ks->update([
            'folder_ks' => json_encode($paths),
            'ks_status_dok' => 1,
        ]);

        $ks->addReviewEntry('Upload Ulang', 'Mitra upload ulang setelah ditolak');
        return redirect()->route('mitra.kerjasama.show', $id)->with('success', 'Dokumen diupload ulang.');
    }

    public function uploadUndangan(Request $request, $id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $request->validate([
            'surat_undangan' => 'required|file|mimes:pdf,docx,zip|max:20480',
        ]);

        $path = $request->file('surat_undangan')->store('dokumen/' . $id, 'public');
        $existing = $ks->folder_ks ? json_decode($ks->folder_ks, true) ?: [] : [];
        $existing[] = $path;
        $ks->update([
            'folder_ks' => json_encode($existing),
            'ks_status_dok' => 3,
        ]);
        $ks->addReviewEntry('Undangan', 'Mitra mengupload surat undangan pembahasan');

        return redirect()->route('mitra.kerjasama.show', $id)->with('info', 'Surat undangan berhasil diupload. Silakan melakukan pembahasan dengan admin sesuai tanggal di surat undangan.');
    }
}
