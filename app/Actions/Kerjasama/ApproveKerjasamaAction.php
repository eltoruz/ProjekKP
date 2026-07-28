<?php

namespace App\Actions\Kerjasama;

use App\Models\Kerjasama;
use App\Models\AppNotification;
use Illuminate\Support\Facades\DB;

class ApproveKerjasamaAction
{
    public function execute(string $kerjasamaId, int $statusDok, string $label, string $catatan): Kerjasama
    {
        return DB::transaction(function () use ($kerjasamaId, $statusDok, $label, $catatan) {
            $ks = Kerjasama::where('kerjasama_id', $kerjasamaId)->notDeleted()->firstOrFail();
            $ks->update(['ks_status_dok' => $statusDok]);

            $ks->addReviewEntry($label, $catatan);

            // Create notification for Mitra
            AppNotification::create([
                'kerjasama_id' => $ks->kerjasama_id,
                'target_role' => 'mitra',
                'title' => "Status Kerja Sama: {$label}",
                'message' => "Pengajuan kerja sama '{$ks->nama_kl}' telah diperbarui ke status '{$label}'.",
                'url' => route('mitra.kerjasama.show', $ks->kerjasama_id),
                'is_read' => false,
            ]);

            return $ks;
        });
    }
}
