<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;
use App\Models\Metadata;
use App\Models\MetadataUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IntegrationController extends Controller
{
    private function resolveUser()
    {
        return User::where('role', 'mitra')->first() ?? auth()->user() ?? User::firstOrCreate(
            ['email' => 'mitradummy@kemendikbud.go.id'],
            [
                'name' => 'Mitra Contoh (Pemerintah Daerah)',
                'role' => 'mitra',
                'password' => bcrypt('password')
            ]
        );
    }

    public function index($id)
    {
        $kerjasama = Kerjasama::notDeleted()->findOrFail($id);
        $user = $this->resolveUser();

        // Get all metadata columns mapped cleanly to reduce payload size
        $allMetadata = Metadata::orderBy('db_name')->orderBy('tbl_name')->orderBy('seq')->get();
        
        // Group columns for JS lookup
        $dbMetadataJson = $allMetadata->groupBy('db_name')->map(function ($dbItems) {
            return $dbItems->groupBy('tbl_name')->map(function ($columns) {
                return $columns->map(function ($col) {
                    return [
                        'id' => strtolower($col->id),
                        'name' => $col->name,
                        'type' => $col->type,
                        'primary_key' => (bool)$col->primary_key,
                        'nullable' => (bool)$col->nullable,
                        'description' => $col->description
                    ];
                });
            });
        });

        // Get table headers for HTML rendering (very lightweight)
        $groupedMetadata = $allMetadata->groupBy('db_name')->map(function ($dbItems) {
            return $dbItems->groupBy('tbl_name')->map(function ($columns) {
                return [
                    'schema_name' => $columns->first()->schema_name ?? 'dbo',
                    'column_ids' => $columns->pluck('id')->map(fn($id)=>strtolower($id))->toArray(),
                    'total_columns' => count($columns)
                ];
            });
        });

        // Get selected metadata IDs for this user, mapped to lowercase
        $selectedMetadataIds = MetadataUser::where('user_id', $user->id)
            ->where('soft_delete', 0)
            ->pluck('metadata_id')
            ->map(fn($id) => strtolower($id))
            ->toArray();

        return view('mitra.kerjasama.integrasi', compact('kerjasama', 'groupedMetadata', 'dbMetadataJson', 'selectedMetadataIds', 'user'));
    }

    public function store(Request $request, $id)
    {
        $kerjasama = Kerjasama::notDeleted()->findOrFail($id);
        $user = $this->resolveUser();

        $selectedIds = json_decode($request->input('selected_metadata_json', '[]'), true);

        // Clear previous selections for this user
        MetadataUser::where('user_id', $user->id)->delete();

        $now = now();
        $insertRows = [];

        foreach ($selectedIds as $metaId) {
            $insertRows[] = [
                'id' => (string) Str::uuid(),
                'metadata_id' => $metaId,
                'user_id' => $user->id,
                'is_masked' => 0,
                'soft_delete' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($insertRows)) {
            // Bulk insert in chunks of 100
            foreach (array_chunk($insertRows, 100) as $chunk) {
                MetadataUser::insert($chunk);
            }
        }

        return redirect()->route('mitra.kerjasama.show', $id)
            ->with('success', 'Pengajuan integrasi data berhasil disimpan!');
    }
}
