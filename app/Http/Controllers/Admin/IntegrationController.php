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
        $approvedIds = $request->input('approved_metadata', []);

        if (!empty($approvedIds)) {
            // Keep approved ones, soft-delete unapproved ones
            MetadataUser::where('user_id', $user->id)
                ->whereIn('id', $approvedIds)
                ->update(['soft_delete' => 0]);

            MetadataUser::where('user_id', $user->id)
                ->whereNotIn('id', $approvedIds)
                ->update(['soft_delete' => 1]);
        } else {
            // If none checked, remove all for this user
            MetadataUser::where('user_id', $user->id)->update(['soft_delete' => 1]);
        }

        $kerjasama->update(['status_integrasi' => 'approved']);

        return redirect()->route('admin.kerjasama.review', $id)
            ->with('success', 'Pengajuan integrasi data berhasil disetujui!');
    }
}
