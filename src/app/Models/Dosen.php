<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dosen extends Model
{
    protected $guarded = ['id'];

    public function jadwal(): BelongsToMany
    {
        return $this->belongsToMany(Jadwal::class);
    }
    public function matakuliah(): BelongsToMany
    {
        return $this->belongsToMany(MataKuliah::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
