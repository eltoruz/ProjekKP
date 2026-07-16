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
            Stat::make('Menunggu Review', Kerjasama::notDeleted()->byStatus('DIAJUKAN')->count())->color('warning'),
            Stat::make('Ditolak', Kerjasama::notDeleted()->byStatus('DITOLAK')->count())->color('danger'),
            Stat::make('Disetujui', Kerjasama::notDeleted()->whereIn('status_pengajuan', ['DISETUJUI','MENUNGGU_PEMBAHASAN','SELESAI_PEMBAHASAN'])->count())->color('success'),
            Stat::make('Selesai', Kerjasama::notDeleted()->byStatus('SELESAI')->count())->color('green'),
            Stat::make('Berakhir', Kerjasama::notDeleted()->byStatus('EXPIRED')->count())->color('gray'),
        ];
    }
}
