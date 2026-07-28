<?php

namespace App\Enums;

enum KsImplementasiEnum: int
{
    case PROSES = 1;
    case DIKIRIM = 2;
    case AKTIF = 3;

    public function label(): string
    {
        return match($this) {
            self::PROSES => 'Dalam Proses',
            self::DIKIRIM => 'Data Dikirim',
            self::AKTIF => 'Aktif Berjalan',
        };
    }
}
