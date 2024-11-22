<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SantriKelas extends Model
{
    use HasFactory;

    protected $table = 'santri_kelas';

    protected $fillable = ['santri_id', 'kelas_id', 'tahun_ajaran_id'];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
