<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;
use App\Models\MitraReport;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function daftar(Request $request)
    {
        $query = Kerjasama::notDeleted()
            ->where('ks_status_dok', '>=', 5)
            ->with(['jenis', 'implementasi', 'reports', 'pemilihanData'])
            ->orderBy('last_update', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kl', 'like', "%{$search}%")
                  ->orWhere('tentang', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate(15)->withQueryString();

        return view('mitra.pelaporan.index', compact('items'));
    }

    public function index($id)
    {
        $ks = Kerjasama::with(['reports', 'pemilihanData.metadata', 'implementasi'])
            ->where('kerjasama_id', $id)->notDeleted()->firstOrFail();

        $isReportingActive = $ks->is_reporting_active;
        $approvedItems = $ks->pemilihanData->where('approval_status', 'approved');

        return view('mitra.kerjasama.laporan', compact('ks', 'isReportingActive', 'approvedItems'));
    }

    public function store(Request $request, $id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();

        if (!$ks->is_reporting_active) {
            return back()->with('error', 'Gagal: Fitur pelaporan berkala belum aktif. Syarat: Minimal 1 item data disetujui Admin dan Status Implementasi diset Aktif.');
        }

        $validated = $request->validate([
            'tahun' => 'required|integer|min:2020|max:2099',
            'periode' => 'required|string|in:Semester 1,Semester 2',
            'file_laporan' => 'required|file|mimes:pdf|max:20480',
            'catatan' => 'nullable|string|max:1000',
        ], [
            'file_laporan.required' => 'File laporan berkala wajib diunggah.',
            'file_laporan.mimes' => 'Format file yang diizinkan hanya: PDF.',
            'file_laporan.max' => 'Ukuran file laporan maksimal 20MB.',
            'tahun.required' => 'Tahun laporan wajib dipilih.',
            'periode.required' => 'Periode semester laporan wajib dipilih.',
        ]);

        $filePath = $request->file('file_laporan')->store('laporan/' . $id, 'public');
        $originalName = $request->file('file_laporan')->getClientOriginalName();

        MitraReport::create([
            'kerjasama_id' => $id,
            'tahun' => $validated['tahun'],
            'periode' => $validated['periode'],
            'file_path' => $filePath,
            'nama_file' => $originalName,
            'catatan' => $validated['catatan'] ?? null,
            'status' => 'dikirim',
        ]);

        $ks->addReviewEntry('Laporan Berkala', "Mitra mengunggah laporan berkala {$validated['periode']} {$validated['tahun']}");

        return redirect()->route('mitra.kerjasama.laporan', $id)
            ->with('success', "Laporan berkala {$validated['periode']} {$validated['tahun']} berhasil diunggah.");
    }
}
