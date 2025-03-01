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
        return $this->hasOne(Nilai::class, 'santri_id')
            ->where('mapel_id', auth()->user()->guru->mapel->id ?? null)
            ->where('tahun_ajaran_id', request('tahun_ajaran_id'));
    }


    public function waliSantri()
    {
        return $this->hasOne(WaliSantri::class);
    }

    public function hafalan()
    {
        return $this->hasMany(SetorHafalan::class);
    }
    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
    public function getTotalHAttribute()
    {
        $tahunAjaranId = request('tahun_ajaran_id');
        return $this->absensi()
            ->where('status', 'H')
            ->when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->count();
    }

    public function getTotalIAttribute()
    {
        $tahunAjaranId = request('tahun_ajaran_id');
        return $this->absensi()
            ->where('status', 'I')
            ->when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->count();
    }

    public function getTotalSAttribute()
    {
        $tahunAjaranId = request('tahun_ajaran_id');
        return $this->absensi()
            ->where('status', 'S')
            ->when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->count();
    }

    public function getTotalAAttribute()
    {
        $tahunAjaranId = request('tahun_ajaran_id');
        return $this->absensi()
            ->where('status', 'A')
            ->when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->count();
    }
}
