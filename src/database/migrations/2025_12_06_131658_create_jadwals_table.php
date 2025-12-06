<?php

use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ProgramStudi::class);
            $table->foreignIdFor(MataKuliah::class);
            $table->string('jam');
            $table->date('tanggal');
            $table->foreignIdFor(Dosen::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
