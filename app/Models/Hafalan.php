<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hafalan extends Model
{
    use HasFactory;

    protected $table = 'hafalan';

    protected $fillable = [
        'nama',
        'kelas_id',
        'target',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id'); 
    }

}
