<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    use HasFactory;
    protected $table = 'disposisi';
    protected $guarded = ['id'];

    public function arsip()
    {
        return $this->belongsTo(Arsip::class, 'arsip_id');
    }
    public function suratMasuk()
    {
        return $this->belongsTo(Arsip::class, 'arsip_id');
    }

    // Model Disposisi
    public function tujuanUsers()
    {
        return $this->belongsToMany(User::class, 'disposisi_user', 'disposisi_id', 'user_id')
            ->withPivot('status_disposisi', 'keterangan', 'created_at')
            ->withTimestamps();
    }

    public function allUsersDisposisi()
    {
        return $this->tujuanUsers->every(function ($tujuanUser) {
            return $tujuanUser->pivot->status_disposisi === 'disposisi';
        });
    }
}
