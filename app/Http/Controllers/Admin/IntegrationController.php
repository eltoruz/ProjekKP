<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;
use App\Models\MetadataUser;
use App\Models\User;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    public function index($id)
    {
        $kerjasama = Kerjasama::notDeleted()->findOrFail($id);

        // Get user (fallback to dummy mitra if no specific user)
        $user = User::where('role', 'mitra')->first() ?? User::first();

        $selectedMetadata = MetadataUser::with('metadata')
            ->where('user_id', $user->id)
            ->where('soft_delete', 0)
            ->get();

        $groupedSelections = $selectedMetadata->groupBy(function ($item) {
            return $item->metadata->tbl_name ?? 'Lainnya';
        });

        return view('admin.kerjasama.integrasi-review', compact('kerjasama', 'user', 'groupedSelections'));
    }

    public function update(Request $request, $id)
    {
        $kerjasama = Kerjasama::notDeleted()->findOrFail($id);

        $user = User::where('role', 'mitra')->first() ?? User::first();
        $maskedIds = $request->input('masked_metadata', []);

        // Reset all to 0 for this user
        MetadataUser::where('user_id', $user->id)->update(['is_masked' => 0]);

        if (!empty($maskedIds)) {
            MetadataUser::where('user_id', $user->id)
                ->whereIn('id', $maskedIds)
                ->update(['is_masked' => 1]);
        }

        $kerjasama->update(['ks_status_integrasi' => 'Selesai Disetujui']);

        return redirect()->route('admin.kerjasama.review', $id)
            ->with('success', 'Pengaturan sensor/masking data berhasil diperbarui!');
    }
}
