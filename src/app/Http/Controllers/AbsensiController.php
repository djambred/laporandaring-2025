<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'npm' => 'nullable|string',
            'nama' => 'required|string|max:255',
            'status' => 'required|in:hadir,izin,sakit',
        ]);

        try {
            // Cari atau buat mahasiswa berdasarkan NPM (jika ada) atau nama
            if (!empty($validated['npm'])) {
                // Jika ada NPM, cari atau buat berdasarkan NPM
                $mahasiswa = Mahasiswa::firstOrCreate(
                    ['npm' => $validated['npm']],
                    ['nama' => $validated['nama']]
                );
            } else {
                // Jika tidak ada NPM, buat mahasiswa baru dengan nama saja
                $mahasiswa = Mahasiswa::create([
                    'nama' => $validated['nama'],
                    'npm' => null,
                ]);
            }

            // Cek apakah sudah absen
            $existingAbsensi = Absensi::where('jadwal_id', $validated['jadwal_id'])
                ->where('mahasiswa_id', $mahasiswa->id)
                ->first();

            if ($existingAbsensi) {
                return back()->with('error', 'Anda sudah melakukan absensi untuk jadwal ini!');
            }

            // Buat absensi
            Absensi::create([
                'jadwal_id' => $validated['jadwal_id'],
                'mahasiswa_id' => $mahasiswa->id,
                'status' => $validated['status'],
                'waktu_absen' => now(),
            ]);

            return redirect('/')->with('success', 'Absensi berhasil dicatat!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function riwayat(Request $request)
    {
        $npm = $request->input('npm');

        if (!$npm) {
            return view('absensi.riwayat');
        }

        $mahasiswa = Mahasiswa::where('npm', $npm)->first();

        if (!$mahasiswa) {
            return view('absensi.riwayat')->with('error', 'NPM tidak ditemukan!');
        }

        $absensis = Absensi::with(['jadwal.matakuliah', 'jadwal.dosen', 'jadwal.programstudi'])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('absensi.riwayat', compact('mahasiswa', 'absensis'));
    }
}
