<?php

namespace App\Http\Controllers;

use App\Models\Kerjasama;
use App\Models\KerjasamaChat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function getMessages($id)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();

        $chats = KerjasamaChat::where('kerjasama_id', $ks->kerjasama_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'chats' => $chats->map(function ($chat) {
                return [
                    'id' => $chat->id,
                    'sender_role' => $chat->sender_role,
                    'sender_name' => $chat->sender_name,
                    'pesan' => $chat->pesan,
                    'attachment_url' => $chat->attachment_path ? asset('storage/' . $chat->attachment_path) : null,
                    'attachment_name' => $chat->attachment_path ? basename($chat->attachment_path) : null,
                    'created_at' => $chat->created_at ? $chat->created_at->format('d M Y, H:i') : '',
                ];
            }),
        ]);
    }

    public function sendMessage(Request $request, $id, \App\Actions\Chat\SendChatMessageAction $action)
    {
        $ks = Kerjasama::where('kerjasama_id', $id)->notDeleted()->firstOrFail();

        $request->validate([
            'sender_role' => 'required|in:admin,mitra',
            'sender_name' => 'required|string|max:100',
            'pesan' => 'required_without:attachment|nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,docx,zip,png,jpg,jpeg|max:10240',
        ]);

        $pesan = $request->pesan ?? 'Mengirimkan berkas lampiran revisi.';
        $attachment = $request->file('attachment');

        $chat = $action->execute(
            $ks->kerjasama_id,
            $request->sender_role,
            $request->sender_name,
            $pesan,
            $attachment
        );

        return response()->json([
            'status' => 'success',
            'chat' => [
                'id' => $chat->id,
                'sender_role' => $chat->sender_role,
                'sender_name' => $chat->sender_name,
                'pesan' => $chat->pesan,
                'attachment_url' => $chat->attachment_path ? asset('storage/' . $chat->attachment_path) : null,
                'attachment_name' => $chat->attachment_name,
                'created_at' => $chat->created_at ? $chat->created_at->format('d M Y, H:i') : '',
            ]
        ]);
    }
}
