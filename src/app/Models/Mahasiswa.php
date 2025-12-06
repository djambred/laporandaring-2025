<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Model
{
    protected $guarded = ['id'];

    /**
     * Mata kuliah yang diambil mahasiswa (KRS)
     */
    public function matakuliahs(): BelongsToMany
    {
        return $this->belongsToMany(MataKuliah::class, 'mahasiswa_mata_kuliah')
            ->withPivot('semester', 'tahun_ajaran', 'status')
            ->withTimestamps();
    }

    /**
     * Jadwal yang diikuti mahasiswa
     */
    public function jadwals(): BelongsToMany
    {
        return $this->belongsToMany(Jadwal::class, 'jadwal_mahasiswa')
            ->withTimestamps();
    }

    /**
     * Absensi mahasiswa
     */
    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }
}
