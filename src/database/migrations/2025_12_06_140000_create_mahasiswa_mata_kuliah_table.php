<?php

use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mahasiswa_mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Mahasiswa::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(MataKuliah::class)->constrained()->onDelete('cascade');
            $table->string('semester'); // Semester berapa mahasiswa mengambil MK ini
            $table->string('tahun_ajaran'); // Contoh: 2025/2026
            $table->enum('status', ['aktif', 'lulus', 'mengulang', 'batal'])->default('aktif');
            $table->timestamps();

            // Prevent duplicate enrollment with custom index name
            $table->unique(['mahasiswa_id', 'mata_kuliah_id', 'semester', 'tahun_ajaran'], 'mhs_mk_sem_ta_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_mata_kuliah');
    }
};
