<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;
use App\Services\WorkflowService;
use Illuminate\Http\Request;

class PembahasanController extends Controller
{
    public function schedule(Request $request, $id)
    {
        $request->validate([
            'tanggal_pembahasan_ks' => 'required|date',
            'jam_pembahasan' => 'required',
            'lokasi_pembahasan' => 'required|string|max:200',
            'link_meeting' => 'nullable|string|max:200',
            'pic_pembahasan' => 'nullable|string|max:200',
        ]);
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $ks->update($request->only(['tanggal_pembahasan_ks','jam_pembahasan','lokasi_pembahasan','link_meeting','pic_pembahasan']));
        (new WorkflowService)->transition($ks, 'MENUNGGU_PEMBAHASAN');
        $ks->addReviewEntry('JADWAL', "Pembahasan: {$request->tanggal_pembahasan_ks} di {$request->lokasi_pembahasan}");
        return back()->with('success', 'Jadwal disimpan.');
    }

    public function completePembahasan(Request $request, $id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $ks->update($request->validate([
            'nomor_pihak1' => 'nullable|string|max:100',
            'nomor_pihak2' => 'nullable|string|max:100',
        ]));
        (new WorkflowService)->transition($ks, 'SELESAI_PEMBAHASAN');
        return back()->with('success', 'Pembahasan selesai, silakan upload dokumen final.');
    }

    public function completeTtd(Request $request, $id)
    {
        $request->validate([
            'dokumen_ttd' => 'required|file|mimes:pdf,docx,zip|max:20480',
            'tanggal_ttd' => 'required|date',
            'ttd_pihak1' => 'nullable|string|max:200',
            'ttd_pihak2' => 'nullable|string|max:200',
        ]);
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();

        // Upload dokumen bertanda tangan
        $path = $request->file('dokumen_ttd')->store('dokumen/' . $id, 'public');
        $existing = $ks->dokumen_ks ? json_decode($ks->dokumen_ks, true) ?: [] : [];
        $ks->update([
            'dokumen_ks' => json_encode(array_merge($existing, [$path])),
            'tanggal_ttd' => $request->tanggal_ttd,
            'ttd_pihak1' => $request->ttd_pihak1,
            'ttd_pihak2' => $request->ttd_pihak2,
        ]);

        $ks->addReviewEntry('TTD_COMPLETE', 'Penandatanganan selesai pada ' . $request->tanggal_ttd);
        (new WorkflowService)->transition($ks, 'SELESAI');
        return back()->with('success', 'Penandatanganan selesai.');
    }

    public function saveMetode(Request $request, $id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $ks->update($request->validate([
            'ks_metode' => 'nullable|exists:ks_metode,id',
            'ks_implementasi' => 'nullable|exists:ks_implementasi,id',
            'jangka_waktu_thn' => 'nullable|integer|min:1',
            'tanggal_mulai_ks' => 'nullable|date',
            'tanggal_selesai_ks' => 'nullable|date',
            'pusdatin_kirim_data' => 'nullable|string',
            'pusdatin_terima_data' => 'nullable|string',
        ]));
        return back()->with('success', 'Data final disimpan.');
    }
}
