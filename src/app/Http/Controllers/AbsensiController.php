<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function index()
    {
        // Tampilkan jadwal hari ini yang tersedia untuk absensi
        $jadwals = Jadwal::with(['matakuliah', 'programstudi', 'dosen'])
            ->whereDate('tanggal', today())
            ->orderBy('jam')
            ->get();

        return view('absensi.index', compact('jadwals'));
    }

    public function create(Jadwal $jadwal)
    {
        return view('absensi.create', compact('jadwal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'npm' => 'required|string',
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'foto_absen' => 'nullable|image|max:2048',
        ]);

        try {
            // Cari atau buat mahasiswa berdasarkan NPM
            $mahasiswa = Mahasiswa::firstOrCreate(
                ['npm' => $validated['npm']],
                ['nama' => $validated['nama']]
            );

            // Cek apakah sudah absen
            $existingAbsensi = Absensi::where('jadwal_id', $validated['jadwal_id'])
                ->where('mahasiswa_id', $mahasiswa->id)
                ->first();

            if ($existingAbsensi) {
                return back()->with('error', 'Anda sudah melakukan absensi untuk jadwal ini!');
            }

            // Simpan foto jika ada
            $fotoPath = null;
            if ($request->hasFile('foto_absen')) {
                $fotoPath = $request->file('foto_absen')->store('absensi-foto', 'public');
            }

            // Buat absensi
            Absensi::create([
                'jadwal_id' => $validated['jadwal_id'],
                'mahasiswa_id' => $mahasiswa->id,
                'status' => 'hadir',
                'waktu_absen' => now(),
                'keterangan' => $validated['keterangan'],
                'foto_absen' => $fotoPath,
                'lokasi' => $request->input('lokasi'), // GPS dari browser
            ]);

            return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dicatat!');

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

        $absensis = Absensi::with(['jadwal.matakuliah', 'jadwal.dosen'])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('absensi.riwayat', compact('mahasiswa', 'absensis'));
    }
}
