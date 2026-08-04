<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MetadataSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = storage_path('app/metadata_seed.json');
        if (!File::exists($jsonPath)) {
            return;
        }

        $items = json_decode(File::get($jsonPath), true);
        if (!is_array($items) || empty($items)) {
            return;
        }

        $chunks = array_chunk($items, 200);
        foreach ($chunks as $chunk) {
            $data = [];
            foreach ($chunk as $row) {
                $data[] = [
                    'id' => $row['id'] ?? (string) \Illuminate\Support\Str::uuid(),
                    'db_name' => $row['db_name'] ?? null,
                    'schema_name' => $row['schema_name'] ?? null,
                    'tbl_name' => $row['tbl_name'] ?? null,
                    'name' => $row['name'] ?? null,
                    'type' => $row['type'] ?? null,
                    'type_name' => $row['type_name'] ?? null,
                    'description' => $row['description'] ?? null,
                    'seq' => $row['seq'] ?? 0,
                    'create_date' => now(),
                    'last_update' => now(),
                ];
            }
            try {
                DB::table('metadata')->insert($data);
            } catch (\Throwable $e) {
                foreach ($data as $d) {
                    try { DB::table('metadata')->insert($d); } catch (\Throwable $ex) {}
                }
            }
        }
    }
}
