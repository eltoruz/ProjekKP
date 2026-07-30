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

            // Insert new selection using Batch Insert (eliminates N+1 queries)
            $now = \Carbon\Carbon::now();
            $insertData = [];
            foreach ($selectedData as $metadataId) {
                $reason = trim($reasons[$metadataId] ?? '');
                $insertData[] = [
                    'kerjasama_id'    => $kerjasamaId,
                    'metadata_id'     => $metadataId,
                    'alasan'          => $reason,
                    'approval_status' => 'pending',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ];
            }

            if (!empty($insertData)) {
                foreach (array_chunk($insertData, 500) as $chunk) {
                    MetadataUser::insert($chunk);
                }
            }

            $isSubmitted = ($action === 'submit');
            $ks->update([
                'status_pemilihan_data' => $isSubmitted ? 'submitted' : 'draft',
            ]);

            return $ks;
        });
    }
}
