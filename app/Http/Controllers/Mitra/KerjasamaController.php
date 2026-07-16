<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;
use App\Models\KsJenis;
use App\Models\KsTingkat;
use App\Models\KsMetode;
use App\Models\KsImplementasi;
use App\Services\WorkflowService;
use Illuminate\Http\Request;

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
        if ($request->status) $query->where('status_pengajuan', $request->status);
        if ($request->jenis) $query->where('ks_jenis', $request->jenis);

        $kerjasamas = $query->orderBy('last_update', 'desc')->paginate(15);
        $statuses = WorkflowService::STATUS;
        $jenisList = KsJenis::all();

        return view('mitra.kerjasama.index', compact('kerjasamas', 'statuses', 'jenisList'));
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
            'kode_wilayah' => 'nullable|string|max:20',
            'provinsi' => 'nullable|string|max:100',
            'nama_kl' => 'nullable|string|max:200',
            'jumlah_kl_terlibat' => 'nullable|integer|min:1',
            'pihak1' => 'nullable|string|max:200',
            'pihak2' => 'nullable|string|max:200',
            'tentang' => 'nullable|string',
            'jangka_waktu_thn' => 'nullable|integer|min:1',
            'tanggal_mulai_ks' => 'nullable|date',
            'tanggal_selesai_ks' => 'nullable|date',
            'narahubung_adm' => 'nullable|string|max:200',
            'nomor_cp_adm' => 'nullable|string|max:50',
            'narahubung_teknis' => 'nullable|string|max:200',
            'nomor_cp_teknis' => 'nullable|string|max:50',
            'dokumen_pendukung' => 'nullable|string',
        ]);
        // NK (jenis_id=3): pakai workflow DRAFT
        if ($validated['ks_jenis'] == 3) {
            $validated['status_pengajuan'] = 'DRAFT';
            $validated['ks_status_dok'] = 1;
        }
        $ks = Kerjasama::create($validated);
        return redirect()->route('mitra.kerjasama.show', $ks->kerjasama_id)->with('success', 'Kerja Sama berhasil dibuat.');
    }

    public function show($id)
    {
        $ks = Kerjasama::with(['jenis', 'tingkat', 'statusDok', 'metode', 'implementasi'])
            ->where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $wf = new WorkflowService;
        return view('mitra.kerjasama.show', [
            'kerjasama' => $ks,
            'nextActions' => $wf->getNextActions($ks->status_pengajuan),
            'canEdit' => $wf->canEdit($ks),
        ]);
    }

    public function edit($id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $wf = new WorkflowService;
        if (!$wf->canEdit($ks)) return redirect()->route('mitra.kerjasama.show', $id)->with('error', 'Data tidak dapat diedit.');
        $jenisList = KsJenis::all();
        $tingkatList = KsTingkat::all();
        return view('mitra.kerjasama.edit', compact('ks', 'jenisList', 'tingkatList'));
    }

    public function update(Request $request, $id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $wf = new WorkflowService;
        if (!$wf->canEdit($ks)) return redirect()->route('mitra.kerjasama.show', $id)->with('error', 'Data tidak dapat diedit.');
        $ks->update($request->validate([
            'ks_jenis' => 'required|exists:ks_jenis,id',
            'ks_tingkat' => 'required|exists:ks_tingkat,id',
            'kode_wilayah' => 'nullable|string|max:20',
            'provinsi' => 'nullable|string|max:100',
            'nama_kl' => 'nullable|string|max:200',
            'jumlah_kl_terlibat' => 'nullable|integer|min:1',
            'pihak1' => 'nullable|string|max:200',
            'pihak2' => 'nullable|string|max:200',
            'tentang' => 'nullable|string',
            'jangka_waktu_thn' => 'nullable|integer|min:1',
            'tanggal_mulai_ks' => 'nullable|date',
            'tanggal_selesai_ks' => 'nullable|date',
            'narahubung_adm' => 'nullable|string|max:200',
            'nomor_cp_adm' => 'nullable|string|max:50',
            'narahubung_teknis' => 'nullable|string|max:200',
            'nomor_cp_teknis' => 'nullable|string|max:50',
            'dokumen_pendukung' => 'nullable|string',
        ]));
        return redirect()->route('mitra.kerjasama.show', $id)->with('success', 'Data diperbarui.');
    }

    public function destroy($id)
    {
        Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail()->update(['soft_delete' => true]);
        return redirect()->route('mitra.kerjasama.index')->with('success', 'Dihapus.');
    }
}
