<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $guarded = ['id'];

    public function programstudi(){
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }

    /**
     * Mahasiswa yang mengambil mata kuliah ini
     */
    public function mahasiswas()
    {
        return $this->belongsToMany(Mahasiswa::class, 'mahasiswa_mata_kuliah')
            ->withPivot('semester', 'tahun_ajaran', 'status')
            ->withTimestamps();
    }
}
