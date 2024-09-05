<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'arsip';

    public function tujuanUsers()
    {
        return $this->belongsToMany(User::class, 'arsip_user', 'arsip_id', 'user_id')->withPivot('status_masuk', 'id', 'keterangan');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function disposisi()
    {
        return $this->hasMany(Disposisi::class, 'arsip_id');
    }

    // public function validator()
    // {
    //     return $this->belongsTo(User::class, 'validator', 'id')->withDefault();
    // }
    public function validator()
    {
        return $this->belongsTo(User::class, 'validator', 'id');
    }
}
