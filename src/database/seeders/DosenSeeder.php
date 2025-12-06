<?php

namespace Database\Seeders;

use App\Models\Dosen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dosen::firstOrCreate([
            'user_id' => 2,
            'nama' => 'Dra. Asmawati M.Pd',
            'nidn' => '0021066303',
            'tanda_tangan' => ''
        ]);
    }
}
