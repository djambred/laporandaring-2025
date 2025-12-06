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
    $jadwals = \App\Models\Jadwal::with(['matakuliah', 'dosen', 'programstudi'])
        ->where('is_active', true)
        ->orderBy('tanggal', 'desc')
        ->orderBy('jam', 'asc')
        ->get();

    return view('welcome', compact('jadwals'));
});

// Absensi Routes (Public - untuk mahasiswa)
Route::prefix('absensi')->name('absensi.')->group(function () {
    Route::post('/store', [App\Http\Controllers\AbsensiController::class, 'store'])->name('store');
    Route::get('/riwayat', [App\Http\Controllers\AbsensiController::class, 'riwayat'])->name('riwayat');
});

// Jadwal Routes (untuk download PDF laporan)
Route::get('/jadwal/{jadwal}/download-pdf', [App\Http\Controllers\JadwalController::class, 'downloadPdf'])->name('jadwal.download-pdf');
