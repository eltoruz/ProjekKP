<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mitra\DashboardController as MitraDashboard;
use App\Http\Controllers\Mitra\KerjasamaController as MitraKerjasama;
use App\Http\Controllers\Mitra\NotaKesepakatanController;
use App\Http\Controllers\Mitra\SuratController as MitraSurat;

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
        Route::get('/surat', [MitraSurat::class, 'index'])->name('surat.index');
        Route::get('/surat/create', [MitraSurat::class, 'create'])->name('surat.create');
        Route::post('/surat', [MitraSurat::class, 'store'])->name('surat.store');
        Route::get('/surat/{id}', [MitraSurat::class, 'show'])->name('surat.show');
        Route::get('/surat/{id}/edit', [MitraSurat::class, 'edit'])->name('surat.edit');
        Route::put('/surat/{id}', [MitraSurat::class, 'update'])->name('surat.update');
        Route::get('/surat/{id}/download', [MitraSurat::class, 'download'])->name('surat.download');
        Route::delete('/surat/{id}', [MitraSurat::class, 'destroy'])->name('surat.destroy');
    });
