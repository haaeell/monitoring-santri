<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai_santri';

    protected $fillable = ['santri_kelas_id', 'mapel_id','presensi', 'nilai_uts', 'nilai_uas', 'hafalan', 'peringkat'];

    public function santriKelas()
    {
        return $this->belongsTo(SantriKelas::class);
    }
    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }
}
