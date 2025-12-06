<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MataKuliah::firstOrCreate([
            'program_studi_id' => 1,
            'nama' => 'Keterampilan Produktif 1',
            'sks' => '3',
            'semester' => '1'
        ]);
        MataKuliah::firstOrCreate([
            'program_studi_id' => 1,
            'nama' => 'Bahasa Indonesia',
            'sks' => '2',
            'semester' => '1'
        ]);
        MataKuliah::firstOrCreate([
            'program_studi_id' => 1,
            'nama' => 'Dasar-dasar Penelitian',
            'sks' => '3',
            'semester' => '3'
        ]);
        MataKuliah::firstOrCreate([
            'program_studi_id' => 1,
            'nama' => 'Metode Penelitian dan Pembelajaran Bahasa Indonesia',
            'sks' => '3',
            'semester' => '5'
        ]);
    }
}
