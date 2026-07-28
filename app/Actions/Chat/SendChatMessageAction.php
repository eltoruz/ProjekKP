<?php

namespace App\Actions\Chat;

use App\Models\KerjasamaChat;
use App\Models\AppNotification;
use App\Models\Kerjasama;
use Illuminate\Support\Facades\DB;

class SendChatMessageAction
{
    public function execute(string $kerjasamaId, string $senderRole, string $senderName, string $pesan, $attachment = null): KerjasamaChat
    {
        return DB::transaction(function () use ($kerjasamaId, $senderRole, $senderName, $pesan, $attachment) {
            $attachmentPath = null;
            $attachmentName = null;

            if ($attachment) {
                $attachmentPath = $attachment->store('chat/' . $kerjasamaId, 'public');
                $attachmentName = $attachment->getClientOriginalName();
            }

            $chat = KerjasamaChat::create([
                'kerjasama_id' => $kerjasamaId,
                'sender_role' => $senderRole,
                'sender_name' => $senderName,
                'pesan' => $pesan,
                'attachment_path' => $attachmentPath,
                'attachment_name' => $attachmentName,
            ]);

            // Notify opposite role
            $targetRole = ($senderRole === 'admin') ? 'mitra' : 'admin';
            $ks = Kerjasama::where('kerjasama_id', $kerjasamaId)->notDeleted()->first();
            $namaKl = $ks ? $ks->nama_kl : 'Kerja Sama';

            AppNotification::create([
                'kerjasama_id' => $kerjasamaId,
                'target_role' => $targetRole,
                'title' => "Pesan Diskusi Baru ({$senderName})",
                'message' => "Ada pesan diskusi baru pada MoU '{$namaKl}'.",
                'url' => ($targetRole === 'admin') 
                    ? route('admin.kerjasama.review', $kerjasamaId) 
                    : route('mitra.kerjasama.show', $kerjasamaId),
                'is_read' => false,
            ]);

            return $chat;
        });
    }
}
