<?php

namespace App\Enums;

enum KsJenisEnum: int
{
    case KESEPAKATAN_BERSAMA = 1;
    case PERJANJIAN_KERJASAMA = 2;
    case NOTA_KESEPAKATAN = 3;

    public function label(): string
    {
        return match($this) {
            self::KESEPAKATAN_BERSAMA => 'Kesepakatan Bersama (KSB)',
            self::PERJANJIAN_KERJASAMA => 'Perjanjian Kerja Sama (PKS)',
            self::NOTA_KESEPAKATAN => 'Nota Kesepakatan (NK)',
        };
    }
}
