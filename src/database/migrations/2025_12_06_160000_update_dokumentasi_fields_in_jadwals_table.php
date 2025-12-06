<?php

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
        Schema::table('jadwals', function (Blueprint $table) {
            // Tambah kolom dokumentasi baru (4 field terpisah)
            // Kolom dokumentasi lama tetap ada untuk data existing
            $table->string('dokumentasi_pre')->nullable()->after('is_active');
            $table->string('dokumentasi_whilst_1')->nullable()->after('dokumentasi_pre');
            $table->string('dokumentasi_whilst_2')->nullable()->after('dokumentasi_whilst_1');
            $table->string('dokumentasi_post')->nullable()->after('dokumentasi_whilst_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            // Hapus kolom dokumentasi baru
            $table->dropColumn(['dokumentasi_pre', 'dokumentasi_whilst_1', 'dokumentasi_whilst_2', 'dokumentasi_post']);
        });
    }
};
