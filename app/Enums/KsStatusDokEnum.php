<?php

namespace App\Enums;

enum KsStatusDokEnum: int
{
    case DRAF = 1;
    case PEMBAHASAN = 2;
    case UNDANGAN = 3;
    case FINALISASI = 4;
    case SELESAI = 5;

    public function label(): string
    {
        return match($this) {
            self::DRAF => 'Draf Pengajuan',
            self::PEMBAHASAN => 'Jadwal Pembahasan',
            self::UNDANGAN => 'Unggah Undangan Pembahasan',
            self::FINALISASI => 'Finalisasi Dokumen MoU',
            self::SELESAI => 'Selesai / Final',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::DRAF => 'bg-slate-100 text-slate-700 border-slate-300',
            self::PEMBAHASAN => 'bg-blue-100 text-blue-800 border-blue-300',
            self::UNDANGAN => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            self::FINALISASI => 'bg-amber-100 text-amber-800 border-amber-300',
            self::SELESAI => 'bg-emerald-100 text-emerald-800 border-emerald-300',
        };
    }
}
