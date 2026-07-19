<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;
use App\Models\KsJenis;
use App\Models\KsTingkat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KerjasamaController extends Controller
{
    public function index(Request $request)
    {
        $query = Kerjasama::notDeleted()->with(['jenis', 'tingkat', 'statusDok']);

        if ($request->search) {
            $q = $request->search;
            $query->where(function ($qb) use ($q) {
                $qb->where('nama_kl', 'like', "%{$q}%")
                   ->orWhere('tentang', 'like', "%{$q}%")
                   ->orWhere('pihak1', 'like', "%{$q}%")
                   ->orWhere('pihak2', 'like', "%{$q}%");
            });
        }
        if ($request->status) $query->where('ks_status_dok', $request->status);
        if ($request->jenis) $query->where('ks_jenis', $request->jenis);

        $kerjasamas = $query->orderBy('last_update', 'desc')->paginate(15);
        $jenisList = KsJenis::all();

        return view('mitra.kerjasama.index', compact('kerjasamas', 'jenisList'));
    }

    public function create()
    {
        $jenisList = KsJenis::all();
        $tingkatList = KsTingkat::all();
        return view('mitra.kerjasama.create', compact('jenisList', 'tingkatList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ks_jenis' => 'required|exists:ks_jenis,id',
            'ks_tingkat' => 'required|exists:ks_tingkat,id',
            'nama_kl' => 'required|string|max:200',
            'narahubung_adm' => 'nullable|string|max:200',
            'nomor_cp_adm' => 'nullable|string|max:50',
            'narahubung_teknis' => 'nullable|string|max:200',
            'nomor_cp_teknis' => 'nullable|string|max:50',
        ]);

        if ($validated['ks_jenis'] == 3) {
            $request->validate([
                'surat_permohonan' => 'required|file|mimes:pdf,docx,zip|max:20480',
                'draft_nk' => 'required|file|mimes:pdf,docx,zip|max:20480',
            ]);
        }

        $validated['ks_status_dok'] = null;

        $ks = Kerjasama::create($validated);

        if ($ks->ks_jenis == 3) {
            $paths = [];
            if ($request->hasFile('surat_permohonan')) $paths[] = $request->file('surat_permohonan')->store('dokumen/' . $ks->kerjasama_id, 'public');
            if ($request->hasFile('draft_nk')) $paths[] = $request->file('draft_nk')->store('dokumen/' . $ks->kerjasama_id, 'public');
            $ks->update(['dokumen_ks' => json_encode($paths)]);
        }

        return redirect()->route('mitra.kerjasama.show', $ks->kerjasama_id);
    }

    public function show($id)
    {
        $ks = Kerjasama::with(['jenis', 'tingkat', 'statusDok', 'metode', 'implementasi'])
            ->where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        return view('mitra.kerjasama.show', ['kerjasama' => $ks]);
    }

    public function edit($id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $jenisList = KsJenis::all();
        $tingkatList = KsTingkat::all();
        return view('mitra.kerjasama.edit', ['kerjasama' => $ks, 'jenisList' => $jenisList, 'tingkatList' => $tingkatList]);
    }

    public function update(Request $request, $id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();

        $validated = $request->validate([
            'ks_jenis' => 'required|exists:ks_jenis,id',
            'ks_tingkat' => 'required|exists:ks_tingkat,id',
            'nama_kl' => 'required|string|max:200',
            'narahubung_adm' => 'nullable|string|max:200',
            'nomor_cp_adm' => 'nullable|string|max:50',
            'narahubung_teknis' => 'nullable|string|max:200',
            'nomor_cp_teknis' => 'nullable|string|max:50',
        ]);
        $ks->update($validated);

        return redirect()->route('mitra.kerjasama.show', $id)->with('success', 'Data diperbarui.');
    }

    public function destroy($id)
    {
        Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail()->update(['soft_delete' => true]);
        return redirect()->route('mitra.kerjasama.index')->with('success', 'Dihapus.');
    }
}
