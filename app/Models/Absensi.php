<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'santri_id',
        'mapel_id',
        'status',
        'keterangan',
        'tanggal',
    ];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }

    public $timestamps = false;
}
