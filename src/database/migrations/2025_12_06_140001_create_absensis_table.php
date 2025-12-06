<?php

use App\Models\Jadwal;
use App\Models\Mahasiswa;
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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Jadwal::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Mahasiswa::class)->constrained()->onDelete('cascade');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('alpha');
            $table->timestamp('waktu_absen')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('foto_absen')->nullable(); // Optional: untuk foto selfie absensi
            $table->string('lokasi')->nullable(); // Optional: GPS koordinat
            $table->timestamps();

            // Prevent duplicate absensi for same jadwal
            $table->unique(['jadwal_id', 'mahasiswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
