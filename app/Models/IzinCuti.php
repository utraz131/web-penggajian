<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IzinCuti extends Model
{
    protected $fillable = [
        'pegawai_id',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'status'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }}
