<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetorHafalan extends Model
{
    use HasFactory;

    protected $table = 'setor_hafalan';

    protected $fillable = [
        'santri_id',
        'nama_hafalan',
        'mulai',
        'selesai',
        'total',
        'tanggal_setor',
        'keterangan',
    ];
}
