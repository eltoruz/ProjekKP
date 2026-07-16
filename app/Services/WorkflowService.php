<?php

namespace App\Services;

use App\Models\Kerjasama;

class WorkflowService
{
    const STATUS = [
        'DRAFT' => 'Draft',
        'UPLOAD_DOKUMEN' => 'Upload Dokumen',
        'DIAJUKAN' => 'Diajukan',
        'REVIEW_ADMIN' => 'Review Admin',
        'DITOLAK' => 'Ditolak',
        'DISETUJUI' => 'Disetujui',
        'MENUNGGU_PEMBAHASAN' => 'Menunggu Pembahasan',
        'SELESAI_PEMBAHASAN' => 'Selesai Pembahasan',
        'PROSES_TTD' => 'Proses Penandatanganan',
        'SELESAI' => 'Selesai',
        'EXPIRED' => 'Masa Berlaku Berakhir',
    ];

    const STATUS_DOK_MAP = [
        'DRAFT' => 1,
        'UPLOAD_DOKUMEN' => 1,
        'DIAJUKAN' => 1,
        'REVIEW_ADMIN' => 1,
        'MENUNGGU_PEMBAHASAN' => 2,
        'SELESAI_PEMBAHASAN' => 3,
        'PROSES_TTD' => 4,
        'SELESAI' => 5,
        'EXPIRED' => 6,
    ];

    const ALLOWED_TRANSITIONS = [
        'DRAFT' => ['UPLOAD_DOKUMEN'],
        'UPLOAD_DOKUMEN' => ['DIAJUKAN'],
        'DIAJUKAN' => ['DITOLAK', 'DISETUJUI'],
        'REVIEW_ADMIN' => ['DITOLAK', 'DISETUJUI'],
        'DITOLAK' => ['UPLOAD_DOKUMEN'],
        'DISETUJUI' => ['MENUNGGU_PEMBAHASAN'],
        'MENUNGGU_PEMBAHASAN' => ['SELESAI_PEMBAHASAN'],
        'SELESAI_PEMBAHASAN' => ['PROSES_TTD', 'SELESAI'],
        'PROSES_TTD' => ['SELESAI'],
        'SELESAI' => ['EXPIRED'],
    ];

    const EDITABLE_STATUSES = ['DRAFT', 'DITOLAK'];

    public function canTransition(string $current, string $next): bool
    {
        return in_array($next, self::ALLOWED_TRANSITIONS[$current] ?? []);
    }

    public function canEdit(Kerjasama $ks): bool
    {
        return in_array($ks->status_pengajuan, self::EDITABLE_STATUSES);
    }

    public function transition(Kerjasama $ks, string $nextStatus): void
    {
        if (!$this->canTransition($ks->status_pengajuan, $nextStatus)) {
            throw new \InvalidArgumentException(
                "Transisi dari '{$ks->status_pengajuan}' ke '{$nextStatus}' tidak diizinkan."
            );
        }

        $ks->status_pengajuan = $nextStatus;

        if ($dokId = self::STATUS_DOK_MAP[$nextStatus] ?? null) {
            $ks->ks_status_dok = $dokId;
        }

        $ks->save();
    }

    public function getNextActions(string $currentStatus): array
    {
        return self::ALLOWED_TRANSITIONS[$currentStatus] ?? [];
    }

    public function checkExpired(): void
    {
        Kerjasama::where('status_pengajuan', 'SELESAI')
            ->where('tanggal_selesai_ks', '<', now())
            ->each(function ($ks) {
                $this->transition($ks, 'EXPIRED');
            });
    }
}
