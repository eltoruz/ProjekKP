<?php

namespace App\Actions\Chat;

use App\Models\KerjasamaChat;
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

            return KerjasamaChat::create([
                'kerjasama_id' => $kerjasamaId,
                'sender_role' => $senderRole,
                'sender_name' => $senderName,
                'pesan' => $pesan,
                'attachment_path' => $attachmentPath,
                'attachment_name' => $attachmentName,
            ]);
        });
    }
}
