<?php

namespace App\Actions\Kerjasama;

use App\Models\Kerjasama;
use App\Models\MetadataUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SimpanPemilihanDataAction
{
    public function execute(string $kerjasamaId, array $selectedData, array $reasons, string $action = 'draft')
    {
        return DB::transaction(function () use ($kerjasamaId, $selectedData, $reasons, $action) {
            $ks = Kerjasama::where('kerjasama_id', $kerjasamaId)->notDeleted()->firstOrFail();

            $now = Carbon::now();

            $existing = MetadataUser::where('kerjasama_id', $kerjasamaId)->get()->keyBy('metadata_id');
            $selectedSet = array_fill_keys($selectedData, true);

            $toRemove = $existing->keys()->diff(array_keys($selectedSet))->all();
            if (! empty($toRemove)) {
                MetadataUser::where('kerjasama_id', $kerjasamaId)
                    ->whereIn('metadata_id', $toRemove)
                    ->delete();
            }

            $insertData = [];
            $seen = [];
            foreach ($selectedData as $metadataId) {
                if (isset($seen[$metadataId])) {
                    continue;
                }
                $seen[$metadataId] = true;

                $reason = trim($reasons[$metadataId] ?? '');

                if ($existing->has($metadataId)) {
                    $item = $existing->get($metadataId);
                    if (trim((string) $item->alasan) !== $reason) {
                        $item->alasan = $reason;
                        $item->approval_status = 'pending';
                        $item->catatan_admin = null;
                        $item->save();
                    }

                    continue;
                }

                $insertData[] = [
                    'id' => (string) Str::uuid(),
                    'kerjasama_id' => $kerjasamaId,
                    'metadata_id' => $metadataId,
                    'alasan' => $reason,
                    'approval_status' => 'pending',
                    'create_date' => $now,
                    'last_update' => $now,
                ];
            }

            if (! empty($insertData)) {
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
