<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Illuminate\Support\Facades\Response;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/*
/ END
*/
Route::get('/', function () {
    return view('welcome');
});

// Absensi Routes (Public - untuk mahasiswa)
Route::prefix('absensi')->name('absensi.')->group(function () {
    Route::get('/', [App\Http\Controllers\AbsensiController::class, 'index'])->name('index');
    Route::get('/create/{jadwal}', [App\Http\Controllers\AbsensiController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\AbsensiController::class, 'store'])->name('store');
    Route::get('/riwayat', [App\Http\Controllers\AbsensiController::class, 'riwayat'])->name('riwayat');
});
