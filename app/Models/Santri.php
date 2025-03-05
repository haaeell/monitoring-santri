<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        return $this->hasMany(Nilai::class, 'santri_id');
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
        $kelasId = request('kelas_id');
        $mapelId = optional(Auth::user()->guru)->mapel->id; 

        return $this->absensi()
            ->where('status', 'H')
            ->when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->when($kelasId, fn($q) => $q->where('kelas_id', $kelasId))
            ->when($mapelId, fn($q) => $q->where('mapel_id', $mapelId))
            ->count();
    }

    public function getTotalIAttribute()
    {
        $tahunAjaranId = request('tahun_ajaran_id');
        $kelasId = request('kelas_id');
        $mapelId = optional(Auth::user()->guru)->mapel->id; 

        return $this->absensi()
            ->where('status', 'I')
            ->when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->when($kelasId, fn($q) => $q->where('kelas_id', $kelasId))
            ->when($mapelId, fn($q) => $q->where('mapel_id', $mapelId))
            ->count();
    }

    public function getTotalSAttribute()
    {
        $tahunAjaranId = request('tahun_ajaran_id');
        $kelasId = request('kelas_id');
        $mapelId = optional(Auth::user()->guru)->mapel->id; 
        
        return $this->absensi()
            ->where('status', 'S')
            ->when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->when($kelasId, fn($q) => $q->where('kelas_id', $kelasId))
            ->when($mapelId, fn($q) => $q->where('mapel_id', $mapelId))
            ->count();
    }

    public function getTotalAAttribute()
    {
        $tahunAjaranId = request('tahun_ajaran_id');
        $kelasId = request('kelas_id');
        $mapelId = optional(Auth::user()->guru)->mapel->id; 

        return $this->absensi()
            ->where('status', 'A')
            ->when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->when($kelasId, fn($q) => $q->where('kelas_id', $kelasId))
            ->when($mapelId, fn($q) => $q->where('mapel_id', $mapelId))
            ->count();
    }


    public function hitungRataRata($kelas_id, $tahun_ajaran_id)
    {
        $nilai = $this->nilai()
            ->where('kelas_id', $kelas_id)
            ->where('tahun_ajaran_id', $tahun_ajaran_id)
            ->get();

        $totalNilai = 0;
        $jumlahMapel = $nilai->count();

        if ($jumlahMapel > 0) {
            foreach ($nilai as $n) {
                $nilaiAkhir = ($n->presensi * 0.4) + ($n->nilai_uts * 0.3) + ($n->nilai_uas * 0.3);
                $totalNilai += $nilaiAkhir;
            }
            return round($totalNilai / $jumlahMapel, 2);
        }

        return 0;
    }
}
