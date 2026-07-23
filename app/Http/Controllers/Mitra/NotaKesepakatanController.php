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

    public function simpanPemilihanData(Request $request, $id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        if ((int)$ks->ks_status_dok < 5) {
            return back()->with('error', 'Pemilihan data hanya dapat dilakukan setelah Nota Kesepakatan berstatus Selesai/Final.');
        }

        // 1. Per-column selection (selected_data containing metadata UUIDs)
        if ($request->has('selected_data') && is_array($request->selected_data)) {
            $request->validate([
                'selected_data' => 'required|array|min:1',
                'selected_data.*' => 'required|string|exists:metadata,id',
                'alasan_table' => 'nullable|array',
                'alasan' => 'nullable|array',
            ], [
                'selected_data.required' => 'Minimal 1 kolom/item data wajib dipilih.',
                'selected_data.min' => 'Minimal 1 kolom/item data wajib dipilih.',
            ]);

            \App\Models\MetadataUser::where('kerjasama_id', $id)->update(['soft_delete' => true]);

            foreach ($request->selected_data as $metadataId) {
                $metadata = \App\Models\Metadata::find($metadataId);
                if (!$metadata) continue;

                $tblName = $metadata->tbl_name;
                $reason = trim($request->alasan[$metadataId] ?? $request->alasan_table[$tblName] ?? '');
                if (empty($reason)) {
                    $reason = 'Digunakan untuk kebutuhan integrasi dan sinkronisasi data.';
                }

                \App\Models\MetadataUser::create([
                    'kerjasama_id' => $id,
                    'metadata_id' => $metadataId,
                    'alasan' => $reason,
                    'is_masked' => false,
                    'soft_delete' => false,
                ]);
            }

            $ks->addReviewEntry('Pemilihan Data', 'Mitra menyimpan pemilihan data per-kolom yang diperlukan');
            return redirect()->route('mitra.kerjasama.show', $id)->with('success', 'Pemilihan data yang diperlukan berhasil disimpan.');
        }

        // 2. Whole table selection (selected_tables)
        if ($request->has('selected_tables') && is_array($request->selected_tables)) {
            $request->validate([
                'selected_tables' => 'required|array|min:1',
                'selected_tables.*' => 'required|string',
                'alasan_table' => 'required|array',
            ], [
                'selected_tables.required' => 'Minimal 1 tabel data wajib dipilih.',
                'selected_tables.min' => 'Minimal 1 tabel data wajib dipilih.',
            ]);

            \App\Models\MetadataUser::where('kerjasama_id', $id)->update(['soft_delete' => true]);

            foreach ($request->selected_tables as $tblName) {
                $reason = trim($request->alasan_table[$tblName] ?? '');
                if (empty($reason)) {
                    $reason = 'Digunakan untuk kebutuhan integrasi dan sinkronisasi data.';
                }
                $columns = \App\Models\Metadata::where('tbl_name', $tblName)->pluck('id');
                foreach ($columns as $metadataId) {
                    \App\Models\MetadataUser::create([
                        'kerjasama_id' => $id,
                        'metadata_id' => $metadataId,
                        'alasan' => $reason,
                        'is_masked' => false,
                        'soft_delete' => false,
                    ]);
                }
            }

            $ks->addReviewEntry('Pemilihan Data', 'Mitra menyimpan pemilihan data yang diperlukan');
            return redirect()->route('mitra.kerjasama.show', $id)->with('success', 'Pemilihan data yang diperlukan berhasil disimpan.');
        }

        return back()->with('error', 'Minimal 1 kolom atau tabel data wajib dipilih.');
    }
}
