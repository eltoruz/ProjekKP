<?php

namespace App\Actions\Kerjasama;

use App\Models\Kerjasama;
use Illuminate\Support\Facades\DB;

class CreateKerjasamaAction
{
    public function execute(array $data, $request = null): Kerjasama
    {
        return DB::transaction(function () use ($data, $request) {
            if (empty($data['ks_status_dok'])) {
                $data['ks_status_dok'] = 1;
            }

            $ks = Kerjasama::create($data);

            if ($request && $request->hasFile('dokumen_ks')) {
                $path = $request->file('dokumen_ks')->store('dokumen/' . $ks->kerjasama_id, 'public');
                $ks->update(['dokumen_ks' => $path]);
            }

            if ($request && $request->hasFile('dokumen_pendukung')) {
                $path = $request->file('dokumen_pendukung')->store('dokumen/' . $ks->kerjasama_id, 'public');
                $ks->update(['dokumen_pendukung' => $path]);
            }

            $ks->addReviewEntry('Dibuat', 'Data dibuat oleh Admin');

            return $ks;
        });
    }
}
