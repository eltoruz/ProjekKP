<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Kerjasama;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    Kerjasama::where('ks_status_dok', 5)
        ->where('tanggal_selesai_ks', '<', now())
        ->update(['ks_status_dok' => 6]);
})->daily();
