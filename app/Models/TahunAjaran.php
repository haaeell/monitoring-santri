<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $fillable = ['tahun', 'semester', 'aktif'];

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }

    public function santriKelas()
    {
        return $this->hasMany(SantriKelas::class);
    }
}
