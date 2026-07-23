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

        if ($validated['ks_jenis'] == 3) {
            $validated['ks_tingkat'] = 3;
        }

        $ks = Kerjasama::create($validated);

        if ($ks->ks_jenis == 3) {
            $paths = [];
            if ($request->hasFile('surat_permohonan')) $paths[] = $request->file('surat_permohonan')->store('dokumen/' . $ks->kerjasama_id, 'public');
            if ($request->hasFile('draft_nk')) $paths[] = $request->file('draft_nk')->store('dokumen/' . $ks->kerjasama_id, 'public');
            $ks->update(['folder_ks' => json_encode($paths)]);
        }

        return redirect()->route('mitra.kerjasama.show', $ks->kerjasama_id);
    }

    public function show($id)
    {
        $ks = Kerjasama::with(['jenis', 'tingkat', 'statusDok', 'metode', 'implementasi', 'pemilihanData.metadata'])
            ->where('kerjasama_id', $id)->notDeleted()->firstOrFail();

        return view('mitra.kerjasama.show', [
            'kerjasama' => $ks,
        ]);
    }

    public function pemilihanDataForm($id)
    {
        $ks = Kerjasama::with(['pemilihanData.metadata'])
            ->where('kerjasama_id', $id)->notDeleted()->firstOrFail();

        if ((int)$ks->ks_status_dok < 5) {
            return redirect()->route('mitra.kerjasama.show', $id)
                ->with('error', 'Pemilihan data hanya dapat dilakukan setelah Nota Kesepakatan berstatus Selesai.');
        }

        // Cache static table catalog as plain array for 24 hours (prevents unserialize errors)
        $tableCatalogArray = \Illuminate\Support\Facades\Cache::remember('metadata_table_catalog_v3', 86400, function () {
            return \App\Models\Metadata::selectRaw('db_name, schema_name, tbl_name, COUNT(*) as total_cols')
                ->groupBy('db_name', 'schema_name', 'tbl_name')
                ->orderBy('db_name')
                ->orderBy('schema_name')
                ->orderBy('tbl_name')
                ->get()
                ->toArray();
        });

        $tableCatalog = collect($tableCatalogArray)->map(fn($item) => (object)$item);

        $databaseList = \Illuminate\Support\Facades\Cache::remember('metadata_database_list_v3', 86400, function () use ($tableCatalog) {
            return $tableCatalog->pluck('db_name')->filter()->unique()->values()->toArray();
        });

        // Previously selected columns and reasons
        $existingSelections = $ks->pemilihanData;
        $selectedColIds = $existingSelections->pluck('metadata_id')->toArray();
        $tableReasons = [];
        foreach ($existingSelections as $sel) {
            if ($sel->metadata) {
                $tableReasons[$sel->metadata->tbl_name] = $sel->alasan;
            }
        }

        return view('mitra.kerjasama.pemilihan_data', [
            'kerjasama' => $ks,
            'tableCatalog' => $tableCatalog,
            'databaseList' => $databaseList,
            'selectedColIds' => $selectedColIds,
            'tableReasons' => $tableReasons,
        ]);
    }

    /**
     * AJAX: Load columns for a specific table (cached 130x speedup, ~3ms latency)
     */
    public function getTableColumns(Request $request)
    {
        $request->validate([
            'db_name' => 'required|string',
            'tbl_name' => 'required|string',
        ]);

        $cacheKey = 'meta_cols_v3_' . md5($request->db_name . '_' . $request->tbl_name);
        
        $colsArray = \Illuminate\Support\Facades\Cache::remember($cacheKey, 86400, function () use ($request) {
            return \App\Models\Metadata::where('db_name', $request->db_name)
                ->where('tbl_name', $request->tbl_name)
                ->orderBy('seq')
                ->get(['id', 'name', 'type', 'type_name', 'description'])
                ->toArray();
        });

        return response()->json($colsArray)
            ->header('Cache-Control', 'public, max-age=3600');
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
        if ($validated['ks_jenis'] == 3) {
            $validated['ks_tingkat'] = 3;
        }
        $ks->update($validated);

        return redirect()->route('mitra.kerjasama.show', $id)->with('success', 'Data diperbarui.');
    }

    public function destroy($id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        if (!$ks->canBeDeletedByMitra()) {
            return redirect()->route('mitra.kerjasama.index')->with('error', 'Kerja sama yang sudah diajukan atau ditolak tidak dapat dihapus.');
        }
        $ks->update(['soft_delete' => true]);
        return redirect()->route('mitra.kerjasama.index')->with('success', 'Dihapus.');
    }
}
