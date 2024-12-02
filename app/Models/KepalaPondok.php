<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KepalaPondok extends Model
{
    use HasFactory;

    protected $table = 'kepala_pondok';

    protected $fillable = ['user_id', 'nip', 'alamat', 'no_telepon', 'pendidikan_terakhir'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

