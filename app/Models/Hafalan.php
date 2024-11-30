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
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id'); 
    }

}
