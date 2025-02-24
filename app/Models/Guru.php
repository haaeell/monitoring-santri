<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = ['user_id', 'nip', 'alamat', 'no_telepon', 'pendidikan_terakhir', 'jabatan','jenis_kelamin'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mapel()
    {
        return $this->hasOne(Mapel::class, 'guru_id');
    }
}
