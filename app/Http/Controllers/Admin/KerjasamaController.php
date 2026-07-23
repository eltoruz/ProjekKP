<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;
use App\Models\KsImplementasi;
use App\Models\KsJenis;
use App\Models\KsMetode;
use App\Models\KsStatusDok;
use App\Models\KsTingkat;
use Illuminate\Http\Request;

class KerjasamaController extends Controller
{
    public function index(Request $request)
    {
        $query = Kerjasama::notDeleted()->with(['jenis', 'tingkat', 'statusDok'])
            ->whereNotNull('ks_status_dok')
            ->orderBy('last_update', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kl', 'like', "%{$search}%")
                  ->orWhere('tentang', 'like', "%{$search}%")
                  ->orWhere('no_input', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('ks_status_dok', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('ks_jenis', $request->jenis);
        }

        $kerjasamas = $query->paginate(15)->withQueryString();
        $statusList = KsStatusDok::pluck('nama_status', 'id');
        $jenisList = KsJenis::pluck('nama_jenis', 'id');

        return view('admin.kerjasama.index', compact('kerjasamas', 'statusList', 'jenisList'));
    }

    public function create()
    {
        $jenisList = KsJenis::pluck('nama_jenis', 'id');
        $tingkatList = KsTingkat::pluck('nama_tingkat', 'id');
        $statusList = KsStatusDok::pluck('nama_status', 'id');
        $metodeList = KsMetode::pluck('nama_metode', 'id');
        $implementasiList = KsImplementasi::pluck('nama_status', 'id');
        return view('admin.kerjasama.create', compact('jenisList', 'tingkatList', 'statusList', 'metodeList', 'implementasiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ks_jenis' => 'required|exists:ks_jenis,id',
            'ks_tingkat' => 'required|exists:ks_tingkat,id',
            'nama_kl' => 'required|string|max:200',
            'kode_wilayah' => 'nullable|string|max:20',
            'no_input' => 'nullable|string|max:50',
            'jumlah_kl_terlibat' => 'nullable|integer|min:1',
            'pihak1' => 'nullable|string|max:200',
            'pihak2' => 'nullable|string|max:200',
            'nomor_pihak1' => 'nullable|string|max:100',
            'nomor_pihak2' => 'nullable|string|max:100',
            'ttd_pihak1' => 'nullable|string|max:200',
            'ttd_pihak2' => 'nullable|string|max:200',
            'tentang' => 'nullable|string',
            'jangka_waktu_thn' => 'nullable|integer|min:1',
            'tanggal_mulai_ks' => 'nullable|date',
            'tanggal_selesai_ks' => 'nullable|date',
            'narahubung_adm' => 'nullable|string|max:200',
            'nomor_cp_adm' => 'nullable|string|max:50',
            'narahubung_teknis' => 'nullable|string|max:200',
            'nomor_cp_teknis' => 'nullable|string|max:50',
            'unit_utama_terlibat' => 'nullable|string',
            'ks_status_dok' => 'nullable|exists:ks_status_dok,id',
            'ks_metode' => 'nullable|exists:ks_metode,id',
            'ks_implementasi' => 'nullable|exists:ks_implementasi,id',
        ]);

        $ks = Kerjasama::create($validated);

        if ($request->hasFile('dokumen_ks')) {
            $path = $request->file('dokumen_ks')->store('dokumen/' . $ks->kerjasama_id, 'public');
            $ks->update(['dokumen_ks' => $path]);
        }

        if ($request->hasFile('dokumen_pendukung')) {
            $path = $request->file('dokumen_pendukung')->store('dokumen/' . $ks->kerjasama_id, 'public');
            $ks->update(['dokumen_pendukung' => $path]);
        }

        $ks->addReviewEntry('Dibuat', 'Data dibuat oleh Admin');

        return redirect()->route('admin.kerjasama.review', $ks->kerjasama_id)->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $jenisList = KsJenis::pluck('nama_jenis', 'id');
        $tingkatList = KsTingkat::pluck('nama_tingkat', 'id');
        $statusList = KsStatusDok::pluck('nama_status', 'id');
        $metodeList = KsMetode::pluck('nama_metode', 'id');
        $implementasiList = KsImplementasi::pluck('nama_status', 'id');

        return view('admin.kerjasama.edit', compact('ks', 'jenisList', 'tingkatList', 'statusList', 'metodeList', 'implementasiList'));
    }

    public function update(Request $request, $id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $ks->update($request->only([
            'ks_jenis', 'ks_tingkat', 'kode_wilayah', 'nama_kl', 'jumlah_kl_terlibat',
            'pihak1', 'pihak2', 'tentang', 'jangka_waktu_thn',
            'tanggal_mulai_ks', 'tanggal_selesai_ks',
            'narahubung_adm', 'nomor_cp_adm', 'narahubung_teknis', 'nomor_cp_teknis',
            'unit_utama_terlibat', 'ks_status_dok', 'ks_metode', 'ks_implementasi',
        ]));

        return redirect()->route('admin.kerjasama.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function review($id)
    {
        $ks = Kerjasama::with(['jenis', 'tingkat', 'statusDok', 'metode', 'implementasi', 'reviewLogs'])
            ->where('kerjasama_id', $id)->notDeleted()->firstOrFail();

        $metodeList = KsMetode::pluck('nama_metode', 'id');
        $implementasiList = KsImplementasi::pluck('nama_status', 'id');

        return view('admin.kerjasama.review', compact('ks', 'metodeList', 'implementasiList'));
    }

    public function destroy($id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $ks->update(['soft_delete' => true]);
        return redirect()->route('admin.kerjasama.index')->with('success', 'Data berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids ?? [];
        if (!empty($ids)) {
            Kerjasama::whereIn('kerjasama_id', $ids)->update(['soft_delete' => true]);
        }
        return redirect()->route('admin.kerjasama.index')->with('success', count($ids) . ' data berhasil dihapus.');
    }

    public function setujui(Request $request, $id)
    {
        $request->validate(['tanggal_pembahasan' => 'required|date']);
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $ks->update([
            'ks_status_dok' => 2,
            'tanggal_pembahasan' => $request->tanggal_pembahasan,
        ]);
        $ks->addReviewEntry('Disetujui', 'Pengajuan disetujui, pembahasan dijadwalkan');
        $ks->addReviewEntry('Jadwal', $request->tanggal_pembahasan);
        return redirect()->route('admin.kerjasama.review', $id);
    }

    public function tolak(Request $request, $id)
    {
        $request->validate(['alasan' => 'required|string']);
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $ks->update(['ks_status_dok' => null]);
        $catatan = $request->alasan . ($request->catatan ? ' — ' . $request->catatan : '');
        $ks->addReviewEntry('Ditolak', $catatan);
        return redirect()->route('admin.kerjasama.review', $id)->with('success', 'Pengajuan ditolak.');
    }

    public function jadwalkan(Request $request, $id)
    {
        $request->validate(['tanggal_pembahasan' => 'required|date']);
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $ks->update(['tanggal_pembahasan' => $request->tanggal_pembahasan]);
        $ks->addReviewEntry('Jadwal', 'Pembahasan ' . $request->tanggal_pembahasan);
        return redirect()->route('admin.kerjasama.review', $id)->with('success', 'Jadwal disimpan.');
    }

    public function lanjutPembahasan($id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();
        $ks->update(['ks_status_dok' => 4]);
        $ks->addReviewEntry('Penandatanganan', 'Dokumen masuk proses penandatanganan');
        return redirect()->route('admin.kerjasama.review', $id)->with('success', 'Status diperbarui ke Penandatanganan.');
    }

    public function finalisasi(Request $request, $id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();

        $data = $request->only([
            'ttd_pihak1', 'ttd_pihak2', 'kode_wilayah', 'pihak1', 'pihak2',
            'tentang', 'jumlah_kl_terlibat', 'jangka_waktu_thn',
            'tanggal_mulai_ks', 'tanggal_selesai_ks',
            'nomor_pihak1', 'nomor_pihak2', 'ks_metode', 'ks_implementasi',
        ]);

        if ($request->hasFile('dokumen_final')) {
            $path = $request->file('dokumen_final')->store('dokumen/' . $id, 'public');
            $data['dokumen_ks'] = $path;
        }

        $data['ks_status_dok'] = 5;
        $ks->update($data);
        $ks->addReviewEntry('Finalisasi', 'Kerja sama difinalisasi');

        return redirect()->route('admin.kerjasama.review', $id)->with('success', 'Finalisasi selesai.');
    }

    public function updateFinalisasi(Request $request, $id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();

        $data = $request->only([
            'ttd_pihak1', 'ttd_pihak2', 'kode_wilayah', 'pihak1', 'pihak2',
            'tentang', 'jumlah_kl_terlibat', 'jangka_waktu_thn',
            'tanggal_mulai_ks', 'tanggal_selesai_ks',
            'nomor_pihak1', 'nomor_pihak2', 'ks_metode', 'ks_implementasi',
        ]);

        $ks->update($data);
        $ks->addReviewEntry('Update Final', 'Data final diperbarui');

        return redirect()->route('admin.kerjasama.review', $id)->with('success', 'Data final diperbarui.');
    }
}
