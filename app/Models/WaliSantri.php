<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaliSantri extends Model
{
    use HasFactory;

    protected $table = 'wali_santri';

    protected $fillable = ['user_id', 'santri_id'];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
