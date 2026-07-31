<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    protected $guarded = [];

    // Relasi: 1 Penggajian ini milik 1 Pegawai
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
