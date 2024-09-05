<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    use HasFactory;
    protected $table = 'peserta';
    protected $guarded = ['id'];

    public function rapat()
    {
        return $this->belongsToMany(Rapat::class, 'rapat_peserta')
            ->withPivot('status_kehadiran', 'catatan')
            ->withTimestamps();
    }
}
