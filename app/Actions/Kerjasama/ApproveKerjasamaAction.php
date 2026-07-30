<?php

namespace App\Actions\Kerjasama;

use App\Models\Kerjasama;
use Illuminate\Support\Facades\DB;

class ApproveKerjasamaAction
{
    public function execute(string $kerjasamaId, int $statusDok, string $label, string $catatan): Kerjasama
    {
        return DB::transaction(function () use ($kerjasamaId, $statusDok, $label, $catatan) {
            $ks = Kerjasama::where('kerjasama_id', $kerjasamaId)->notDeleted()->firstOrFail();
            $ks->update(['ks_status_dok' => $statusDok]);

            $ks->addReviewEntry($label, $catatan);

            return $ks;
        });
    }
}
