<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MetadataSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('seeders/metadata.json');

        if (!File::exists($jsonPath)) {
            $this->command->error("File metadata.json not found! Run the python conversion script first.");
            return;
        }

        $this->command->info("Reading metadata.json...");
        $json = File::get($jsonPath);
        $data = json_decode($json, true);

        if (empty($data)) {
            $this->command->error("No data found in metadata.json");
            return;
        }

        $this->command->info("Seeding metadata table (Total: " . count($data) . " rows)...");

        // Clear existing metadata
        DB::table('metadata')->delete();

        $now = now()->format('Y-m-d H:i:s');
        $successCount = 0;

        $cleanDate = function ($val) {
            if (empty($val) || $val === 'NULL' || $val === 'null') return null;
            if (is_string($val) && strlen($val) >= 19) {
                return substr($val, 0, 19);
            }
            return null;
        };

        // Insert in smaller chunks of 50
        $chunks = array_chunk($data, 50);

        foreach ($chunks as $index => $chunk) {
            $insertData = [];
            foreach ($chunk as $row) {
                $insertData[] = [
                    'id' => (string)$row['id'],
                    'db_name' => (string)($row['db_name'] ?? 'Backbone'),
                    'schema_name' => (string)($row['schema_name'] ?? 'dbo'),
                    'tbl_name' => (string)($row['tbl_name'] ?? ''),
                    'name' => (string)($row['name'] ?? ''),
                    'type' => (string)($row['type'] ?? ''),
                    'type_name' => isset($row['type_name']) ? (string)$row['type_name'] : null,
                    'type_length' => is_numeric($row['type_length']) ? (int)$row['type_length'] : null,
                    'type_precision' => is_numeric($row['type_precision']) ? (int)$row['type_precision'] : null,
                    'type_scale' => is_numeric($row['type_scale']) ? (int)$row['type_scale'] : null,
                    'type_collation' => isset($row['type_collation']) ? (string)$row['type_collation'] : null,
                    'nullable' => isset($row['nullable']) ? (int)(bool)$row['nullable'] : 1,
                    'primary_key' => isset($row['primary_key']) ? (int)(bool)$row['primary_key'] : 0,
                    'autoincrement' => isset($row['autoincrement']) ? (int)(bool)$row['autoincrement'] : 0,
                    'is_unique' => isset($row['is_unique']) ? (int)(bool)$row['is_unique'] : 0,
                    'default_value' => isset($row['default_value']) ? (string)$row['default_value'] : null,
                    'description' => isset($row['description']) ? (string)$row['description'] : null,
                    'seq' => is_numeric($row['seq']) ? (int)$row['seq'] : null,
                    'create_date' => $cleanDate($row['create_date'] ?? null),
                    'last_update' => $cleanDate($row['last_update'] ?? null),
                    'expired_date' => $cleanDate($row['expired_date'] ?? null),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            try {
                DB::table('metadata')->insert($insertData);
                $successCount += count($insertData);
            } catch (\Exception $e) {
                // If chunk insert fails, fallback to row-by-row insert for this chunk to bypass bad rows
                foreach ($insertData as $singleRow) {
                    try {
                        DB::table('metadata')->insert($singleRow);
                        $successCount++;
                    } catch (\Exception $ex) {
                        // Log skipping bad row
                    }
                }
            }
        }

        $this->command->info("Metadata seeding completed successfully! Inserted {$successCount} / " . count($data) . " rows.");
    }
}
