<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function downloadPdf(Jadwal $jadwal)
    {
        // Load relasi dengan sorting berdasarkan NPM
        $jadwal->load([
            'matakuliah',
            'programstudi',
            'dosen',
            'absensis' => function ($query) {
                $query->with('mahasiswa')
                      ->join('mahasiswas', 'absensis.mahasiswa_id', '=', 'mahasiswas.id')
                      ->orderByRaw('CAST(mahasiswas.npm AS UNSIGNED) ASC')
                      ->select('absensis.*');
            }
        ]);

        // Hitung statistik
        $totalMahasiswa = $jadwal->absensis->count();
        $hadir = $jadwal->absensis->where('status', 'hadir')->count();
        $izin = $jadwal->absensis->where('status', 'izin')->count();
        $sakit = $jadwal->absensis->where('status', 'sakit')->count();
        $alpha = $jadwal->absensis->where('status', 'alpha')->count();

        // Generate filename dengan format: namadosen-matakuliah-tanggal
        $namaDosen = str_replace(' ', '-', $jadwal->dosen->nama);
        $matakuliah = str_replace(' ', '-', $jadwal->matakuliah->nama);
        $tanggal = $jadwal->tanggal->format('Y-m-d');
        $filename = "{$namaDosen}_{$matakuliah}_{$tanggal}";

        // Jika tidak ada package PDF, kita buat HTML biasa yang bisa di-print
        $html = view('pdf.laporan-absensi', compact('jadwal', 'totalMahasiswa', 'hadir', 'izin', 'sakit', 'alpha'))->render();

        // Return sebagai response dengan header untuk download
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '.html"');
    }
}
