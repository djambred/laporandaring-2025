<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Jadwal extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get all dokumentasi as array for compatibility
     */
    public function getDokumentasiAttribute()
    {
        return array_filter([
            $this->dokumentasi_pre,
            $this->dokumentasi_whilst_1,
            $this->dokumentasi_whilst_2,
            $this->dokumentasi_post,
        ]);
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    public function mahasiswa(): BelongsToMany
    {
        return $this->belongsToMany(Mahasiswa::class, 'jadwal_mahasiswa')
            ->withTimestamps();
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function matakuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    public function programstudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }
}
