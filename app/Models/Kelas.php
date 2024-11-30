<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'walikelas_id',
        'tahun_ajaran_id',
    ];
    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }
    public function hafalan()
    {
        return $this->belongsTo(Hafalan::class, 'hafalan_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function santris()
    {
        return $this->belongsToMany(Santri::class, 'santri_kelas');
    }
    public function mapels()
    {
        return $this->belongsToMany(Mapel::class, 'mapel_kelas');
    }
}
