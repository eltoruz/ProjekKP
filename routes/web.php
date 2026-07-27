<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\KerjasamaController as AdminKerjasama;
use App\Http\Controllers\Mitra\DashboardController as MitraDashboard;
use App\Http\Controllers\Mitra\KerjasamaController as MitraKerjasama;
use App\Http\Controllers\Mitra\NotaKesepakatanController;

Route::get('/', function () {
    return view('auth.role-selection');
})->name('role.select');

Route::get('/admin/login', function () {
    return redirect('/');
});

// Mitra
Route::middleware(['web'])
    ->prefix('mitra')->name('mitra.')->group(function () {
        Route::get('/', [MitraDashboard::class, 'index'])->name('dashboard');
        Route::get('/kerjasama', [MitraKerjasama::class, 'index'])->name('kerjasama.index');
        Route::get('/kerjasama/create', [MitraKerjasama::class, 'create'])->name('kerjasama.create');
        Route::post('/kerjasama', [MitraKerjasama::class, 'store'])->name('kerjasama.store');
        Route::get('/kerjasama/{id}', [MitraKerjasama::class, 'show'])->name('kerjasama.show');
        Route::get('/kerjasama/{id}/edit', [MitraKerjasama::class, 'edit'])->name('kerjasama.edit');
        Route::put('/kerjasama/{id}', [MitraKerjasama::class, 'update'])->name('kerjasama.update');
        Route::delete('/kerjasama/{id}', [MitraKerjasama::class, 'destroy'])->name('kerjasama.destroy');
        Route::post('/kerjasama/{id}/upload', [NotaKesepakatanController::class, 'upload'])->name('kerjasama.upload');
        Route::post('/kerjasama/{id}/ajukan', [NotaKesepakatanController::class, 'ajukan'])->name('kerjasama.ajukan');
        Route::get('/kerjasama/{id}/upload-ulang', [NotaKesepakatanController::class, 'uploadUlangForm'])->name('kerjasama.upload-ulang');
        Route::post('/kerjasama/{id}/upload-ulang', [NotaKesepakatanController::class, 'uploadUlang'])->name('kerjasama.upload-ulang');
        Route::post('/kerjasama/{id}/upload-undangan', [NotaKesepakatanController::class, 'uploadUndangan'])->name('kerjasama.upload-undangan');
        Route::get('/kerjasama/{id}/pemilihan-data', [MitraKerjasama::class, 'pemilihanDataForm'])->name('kerjasama.pemilihan-data.form');
        Route::post('/kerjasama/{id}/pemilihan-data', [NotaKesepakatanController::class, 'simpanPemilihanData'])->name('kerjasama.pemilihan-data');
        Route::post('/kerjasama/{id}/ajukan-pemilihan-data', [NotaKesepakatanController::class, 'ajukanPemilihanData'])->name('kerjasama.ajukan-pemilihan-data');
        Route::get('/kerjasama/{id}/laporan', [\App\Http\Controllers\Mitra\LaporanController::class, 'index'])->name('kerjasama.laporan');
        Route::post('/kerjasama/{id}/laporan', [\App\Http\Controllers\Mitra\LaporanController::class, 'store'])->name('kerjasama.laporan.store');
        Route::get('/kerjasama/{id}/cetak-ringkasan', [NotaKesepakatanController::class, 'cetakRingkasan'])->name('kerjasama.cetak-ringkasan');
        Route::get('/api/metadata/columns', [MitraKerjasama::class, 'getTableColumns'])->name('api.metadata.columns');
    });

// Admin
Route::middleware(['web', \App\Http\Middleware\AutoLoginAdmin::class])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::get('/kerjasama', [AdminKerjasama::class, 'index'])->name('kerjasama.index');
        Route::get('/kerjasama/create', [AdminKerjasama::class, 'create'])->name('kerjasama.create');
        Route::post('/kerjasama', [AdminKerjasama::class, 'store'])->name('kerjasama.store');
        Route::delete('/kerjasama/bulk-delete', [AdminKerjasama::class, 'bulkDelete'])->name('kerjasama.bulkDelete');
        Route::get('/kerjasama/{id}/edit', [AdminKerjasama::class, 'edit'])->name('kerjasama.edit');
        Route::put('/kerjasama/{id}', [AdminKerjasama::class, 'update'])->name('kerjasama.update');
        Route::delete('/kerjasama/{id}', [AdminKerjasama::class, 'destroy'])->name('kerjasama.destroy');
        Route::get('/kerjasama/{id}/review', [AdminKerjasama::class, 'review'])->name('kerjasama.review');
        Route::post('/kerjasama/{id}/setujui', [AdminKerjasama::class, 'setujui'])->name('kerjasama.setujui');
        Route::post('/kerjasama/{id}/tolak', [AdminKerjasama::class, 'tolak'])->name('kerjasama.tolak');
        Route::post('/kerjasama/{id}/jadwalkan', [AdminKerjasama::class, 'jadwalkan'])->name('kerjasama.jadwalkan');
        Route::post('/kerjasama/{id}/lanjut-pembahasan', [AdminKerjasama::class, 'lanjutPembahasan'])->name('kerjasama.lanjutPembahasan');
        Route::post('/kerjasama/{id}/finalisasi', [AdminKerjasama::class, 'finalisasi'])->name('kerjasama.finalisasi');
        Route::post('/kerjasama/{id}/update-finalisasi', [AdminKerjasama::class, 'updateFinalisasi'])->name('kerjasama.updateFinalisasi');
        Route::get('/kerjasama/{id}/persetujuan-data', [AdminKerjasama::class, 'persetujuanDataForm'])->name('kerjasama.persetujuan-data.form');
        Route::post('/kerjasama/{id}/persetujuan-data', [AdminKerjasama::class, 'simpanPersetujuanData'])->name('kerjasama.persetujuan-data');
        Route::get('/kerjasama/{id}/cetak-ringkasan', [AdminKerjasama::class, 'cetakRingkasan'])->name('kerjasama.cetak-ringkasan');
    });
