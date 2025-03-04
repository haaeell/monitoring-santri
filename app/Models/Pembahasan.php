<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembahasan extends Model
{
    use HasFactory;

protected $table = 'pembahasan';

    protected $fillable = [
        'tanggal', 'guru_id', 'kelas_id', 'mapel_id', 'pembahasan','tahun_ajaran_id','pertemuan','tanggal'
    ];
}
