<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapat extends Model
{
    use HasFactory;
    protected $table = 'rapat';
    protected $guarded = ['id'];

    public function agenda()
    {
        return $this->belongsTo(Agenda::class);
    }

    public function absen()
    {
        return $this->belongsToMany(Peserta::class, 'rapat_peserta')
            ->withPivot('status_kehadiran')
            ->withTimestamps();
    }

    public function peserta()
    {
        return $this->belongsToMany(Peserta::class, 'rapat_peserta', 'rapat_id', 'peserta_id')
            ->withPivot('status_kehadiran')
            ->withTimestamps();
    }
}
