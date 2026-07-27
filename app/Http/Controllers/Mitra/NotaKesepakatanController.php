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

        // Read-only check: cannot edit if already submitted
        if ($ks->status_pemilihan_data === 'submitted') {
            return back()->with('error', 'Pemilihan data telah diajukan dan terkunci. Anda tidak dapat mengubah data yang sudah final.');
        }

        // 1. Per-column selection (selected_data containing metadata UUIDs)
        if ($request->has('selected_data') && is_array($request->selected_data) && count($request->selected_data) > 0) {
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

                \App\Models\MetadataUser::create([
                    'kerjasama_id' => $id,
                    'metadata_id' => $metadataId,
                    'alasan' => $reason,
                    'is_masked' => false,
                    'approval_status' => 'pending',
                    'soft_delete' => false,
                ]);
            }

            $ks->update(['status_pemilihan_data' => 'draft']);
            $ks->addReviewEntry('Pemilihan Data', 'Mitra menyimpan draf pemilihan data');

            return redirect()->route('mitra.kerjasama.show', $id)
                ->with('success', 'Draf pemilihan data berhasil disimpan. Silakan periksa kembali di halaman detail dan klik "Ajukan Pemilihan Data" untuk mengirim ke Admin.');
        }

        // 2. Whole table selection (selected_tables)
        if ($request->has('selected_tables') && is_array($request->selected_tables) && count($request->selected_tables) > 0) {
            $request->validate([
                'selected_tables' => 'required|array|min:1',
                'selected_tables.*' => 'required|string',
                'alasan_table' => 'nullable|array',
            ], [
                'selected_tables.required' => 'Minimal 1 tabel data wajib dipilih.',
                'selected_tables.min' => 'Minimal 1 tabel data wajib dipilih.',
            ]);

            \App\Models\MetadataUser::where('kerjasama_id', $id)->update(['soft_delete' => true]);

            foreach ($request->selected_tables as $tblName) {
                $reason = trim($request->alasan_table[$tblName] ?? '');
                $columns = \App\Models\Metadata::where('tbl_name', $tblName)->pluck('id');
                foreach ($columns as $metadataId) {
                    \App\Models\MetadataUser::create([
                        'kerjasama_id' => $id,
                        'metadata_id' => $metadataId,
                        'alasan' => $reason,
                        'is_masked' => false,
                        'approval_status' => 'pending',
                        'soft_delete' => false,
                    ]);
                }
            }

            $ks->update(['status_pemilihan_data' => 'draft']);
            $ks->addReviewEntry('Pemilihan Data', 'Mitra menyimpan draf pemilihan data');

            return redirect()->route('mitra.kerjasama.show', $id)
                ->with('success', 'Draf pemilihan data berhasil disimpan. Silakan periksa kembali di halaman detail dan klik "Ajukan Pemilihan Data" untuk mengirim ke Admin.');
        }

        // If saving draft with 0 items, mark soft_delete and set status to draft
        \App\Models\MetadataUser::where('kerjasama_id', $id)->update(['soft_delete' => true]);
        $ks->update(['status_pemilihan_data' => 'draft']);
        return redirect()->route('mitra.kerjasama.show', $id)->with('success', 'Draf pemilihan data disimpan.');
    }

    public function ajukanPemilihanData(Request $request, $id)
    {
        $ks = Kerjasama::with('pemilihanData')->where('kerjasama_id', $id)->notDeleted()->firstOrFail();

        if ((int)$ks->ks_status_dok < 5) {
            return back()->with('error', 'Pengajuan data hanya dapat dilakukan setelah Nota Kesepakatan berstatus Selesai/Final.');
        }

        if ($ks->status_pemilihan_data === 'submitted') {
            return back()->with('error', 'Pemilihan data telah diajukan sebelumnya dan terkunci.');
        }

        $items = $ks->pemilihanData;
        if ($items->isEmpty()) {
            return back()->with('error', 'Gagal mengajukan: Anda belum memilih data. Silakan klik "Pilih Data yang Diperlukan" terlebih dahulu.');
        }

        foreach ($items as $item) {
            if (empty(trim($item->alasan ?? ''))) {
                return back()->with('error', 'Gagal mengajukan: Terdapat item data terpilih yang belum memiliki alasan penggunaan. Silakan lengkapi alasan pada formulir pemilihan data.');
            }
        }

        $ks->update(['status_pemilihan_data' => 'submitted']);
        $ks->addReviewEntry('Pemilihan Data', 'Mitra mengajukan pemilihan data secara final (submitted)');

        return redirect()->route('mitra.kerjasama.show', $id)
            ->with('success', 'Pemilihan data berhasil diajukan ke Admin Pusdatin. Data kini telah dikunci (Read-Only) dan tidak dapat diubah lagi.');
    }

    public function cetakRingkasan($id)
    {
        $ks = Kerjasama::with(['jenis', 'tingkat', 'statusDok', 'metode', 'implementasi', 'pemilihanData.metadata'])
            ->where('kerjasama_id', $id)->notDeleted()->firstOrFail();

        return view('admin.kerjasama.cetak_ringkasan', compact('ks'));
    }
}
