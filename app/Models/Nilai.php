<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $fillable = ['santri_id', 'kelas_id', 'mapel_id','presensi', 'nilai_uts', 'nilai_uas', 'hafalan', 'peringkat','tahun_ajaran_id'];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }


}
