<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jadwal::firstOrCreate([
            'program_studi_id' => 1,
            'mata_kuliah_id' => 1,
            'dosen_id' => 1,
            'jam' => '4-6',
            'tanggal' => '2025-12-03'
        ]);

        Jadwal::firstOrCreate([
            'program_studi_id' => 1,
            'mata_kuliah_id' => 2,
            'dosen_id' => 1,
            'jam' => '1-2',
            'tanggal' => '2025-12-01'
        ]);
        Jadwal::firstOrCreate([
            'program_studi_id' => 1,
            'mata_kuliah_id' => 3,
            'dosen_id' => 1,
            'jam' => '1-3',
            'tanggal' => '2025-12-03'
        ]);
        Jadwal::firstOrCreate([
            'program_studi_id' => 1,
            'mata_kuliah_id' => 4,
            'dosen_id' => 1,
            'jam' => '1-3',
            'tanggal' => '2025-12-01'
        ]);
    }
}
