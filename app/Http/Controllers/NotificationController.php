<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getLatest(Request $request)
    {
        $role = strtolower(trim($request->query('role', 'mitra')));

        $notifications = AppNotification::where(function ($q) use ($role) {
                $q->where('target_role', $role)->orWhere('target_role', 'all');
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $unreadCount = AppNotification::where(function ($q) use ($role) {
                $q->where('target_role', $role)->orWhere('target_role', 'all');
            })
            ->where(function ($q) {
                $q->where('is_read', 0)
                  ->orWhereNull('is_read')
                  ->orWhere('is_read', false);
            })
            ->count();

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'message' => $item->message,
                    'type' => $item->type,
                    'url' => $item->url,
                    'is_read' => (bool)$item->is_read,
                    'waktu' => $item->created_at ? $item->created_at->diffForHumans() : 'Baru saja',
                ];
            }),
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $role = $request->input('role', 'mitra');

        AppNotification::whereIn('target_role', [$role, 'all'])
            ->where(function ($q) {
                $q->where('is_read', 0)
                  ->orWhereNull('is_read')
                  ->orWhere('is_read', false);
            })
            ->update(['is_read' => 1]);

        return response()->json(['status' => 'success']);
    }
}
