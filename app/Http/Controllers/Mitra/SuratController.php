<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;
use App\Models\KsJenis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $query = Kerjasama::notDeleted()->with('jenis')
            ->whereNotIn('ks_jenis', [3]);

        if ($request->jenis) $query->where('ks_jenis', $request->jenis);

        $surat = $query->orderBy('last_update', 'desc')->paginate(15);
        $jenisList = KsJenis::whereNotIn('id', [3])->get();

        return view('mitra.surat.index', compact('surat', 'jenisList'));
    }

    public function create()
    {
        $jenisList = KsJenis::whereNotIn('id', [3])->get();
        return view('mitra.surat.create', compact('jenisList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ks_jenis' => 'required|exists:ks_jenis,id|not_in:3',
            'pihak1' => 'nullable|string|max:200',
            'pihak2' => 'nullable|string|max:200',
            'nama_kl' => 'nullable|string|max:200',
            'tentang' => 'nullable|string',
            'tanggal_mulai_ks' => 'nullable|date',
            'dokumen_ks' => 'nullable|file|mimes:pdf,docx,zip|max:20480',
        ]);

        $validated['status_pengajuan'] = 'SELESAI';
        $validated['ks_status_dok'] = 5;
        $validated['ks_tingkat'] = 1;

        if ($request->filled('nomor_surat')) {
            $validated['nomor_pihak1'] = $request->nomor_surat;
        }
        if ($request->filled('perihal')) {
            $validated['tentang'] = $request->perihal;
        }
        if ($request->filled('tanggal_surat')) {
            $validated['tanggal_mulai_ks'] = $request->tanggal_surat;
        }

        if ($request->hasFile('dokumen_ks')) {
            $validated['dokumen_ks'] = json_encode([$request->file('dokumen_ks')->store('surat', 'public')]);
        }

        Kerjasama::create($validated);
        return redirect()->route('mitra.surat.index')->with('success', 'Surat ditambahkan.');
    }

    public function show($id)
    {
        $surat = Kerjasama::with('jenis')->where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        return view('mitra.surat.show', compact('surat'));
    }

    public function edit($id)
    {
        $surat = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $jenisList = KsJenis::whereNotIn('id', [3])->get();
        return view('mitra.surat.edit', compact('surat', 'jenisList'));
    }

    public function update(Request $request, $id)
    {
        $surat = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();

        $validated = $request->validate([
            'ks_jenis' => 'required|exists:ks_jenis,id|not_in:3',
            'pihak1' => 'nullable|string|max:200',
            'pihak2' => 'nullable|string|max:200',
            'nama_kl' => 'nullable|string|max:200',
            'tentang' => 'nullable|string',
            'tanggal_mulai_ks' => 'nullable|date',
            'dokumen_ks' => 'nullable|file|mimes:pdf,docx,zip|max:20480',
        ]);

        if ($request->filled('nomor_surat')) {
            $validated['nomor_pihak1'] = $request->nomor_surat;
        }
        if ($request->filled('perihal')) {
            $validated['tentang'] = $request->perihal;
        }
        if ($request->filled('tanggal_surat')) {
            $validated['tanggal_mulai_ks'] = $request->tanggal_surat;
        }

        if ($request->hasFile('dokumen_ks')) {
            $validated['dokumen_ks'] = json_encode([$request->file('dokumen_ks')->store('surat', 'public')]);
        } else {
            unset($validated['dokumen_ks']);
        }

        unset($validated['nomor_surat'], $validated['perihal'], $validated['tanggal_surat']);
        $surat->update($validated);
        return redirect()->route('mitra.surat.show', $id)->with('success', 'Surat diperbarui.');
    }

    public function download($id)
    {
        $surat = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $files = json_decode($surat->dokumen_ks, true) ?: [];
        if (!$files) return back()->with('error', 'Tidak ada file.');
        return Storage::disk('public')->download($files[0]);
    }

    public function destroy($id)
    {
        Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail()->update(['soft_delete' => true]);
        return redirect()->route('mitra.surat.index')->with('success', 'Dihapus.');
    }
}
