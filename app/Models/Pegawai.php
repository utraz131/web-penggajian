<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $guarded = [];

    public function izinCutis()
    {
        return $this->hasMany(IzinCuti::class);
    }
}
