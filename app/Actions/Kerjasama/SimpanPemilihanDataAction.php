<?php

namespace App\Actions\Kerjasama;

use App\Models\Kerjasama;
use App\Models\MetadataUser;
use Illuminate\Support\Facades\DB;

class SimpanPemilihanDataAction
{
    public function execute(string $kerjasamaId, array $selectedData, array $reasons, string $action = 'draft')
    {
        return DB::transaction(function () use ($kerjasamaId, $selectedData, $reasons, $action) {
            $ks = Kerjasama::where('kerjasama_id', $kerjasamaId)->notDeleted()->firstOrFail();

            // Clear existing selection for this kerjasama
            MetadataUser::where('kerjasama_id', $kerjasamaId)->delete();

            // Insert new selection
            foreach ($selectedData as $metadataId) {
                $reason = trim($reasons[$metadataId] ?? '');
                MetadataUser::create([
                    'kerjasama_id' => $kerjasamaId,
                    'metadata_id' => $metadataId,
                    'alasan' => $reason,
                    'approval_status' => 'pending',
                ]);
            }

            $isSubmitted = ($action === 'submit');
            $ks->update([
                'status_pemilihan_data' => $isSubmitted ? 'submitted' : 'draft',
            ]);

            return $ks;
        });
    }
}
