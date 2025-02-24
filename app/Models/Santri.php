<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    protected $table = 'santri'; 

    protected $fillable = [
        'nama',
        'nis',
        'kamar',
        'jenis_kelamin',
        'alamat',
        'telp',
        'tanggal_lahir',
        'foto',
        'nama_ayah',
        'nama_ibu',
        'kelas_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    public function waliSantri()
    {
        return $this->hasOne(WaliSantri::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
}
