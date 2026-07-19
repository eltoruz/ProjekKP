<?php

namespace App\Filament\Widgets;

use App\Models\Kerjasama;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KerjasamaStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Kerja Sama', Kerjasama::notDeleted()->count())->color('blue'),
            Stat::make('Belum Lengkap', Kerjasama::notDeleted()->byStatus(1)->count())->color('warning'),
            Stat::make('Dalam Pembahasan', Kerjasama::notDeleted()->byStatus(2)->count())->color('orange'),
            Stat::make('Selesai', Kerjasama::notDeleted()->byStatus(5)->count())->color('success'),
            Stat::make('Berakhir', Kerjasama::notDeleted()->byStatus(6)->count())->color('gray'),
        ];
    }
}
