<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        // 'remember_token',
    ];

    public static $rules = [
        'username' => 'required|string|max:255',
        'email' => 'nullable|string|email|max:255',
        // aturan validasi lainnya
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // public function arsipTujuan()
    // {
    //     return $this->belongsToMany(Arsip::class, 'arsip_user', 'user_id', 'arsip_id');
    // }
    public function arsipTujuan()
    {
        return $this->belongsToMany(Arsip::class, 'arsip_user', 'user_id', 'arsip_id')->withPivot('status_masuk', 'keterangan');
    }




    public function disposisiTujuan()
    {
        return $this->belongsToMany(Disposisi::class, 'disposisi_user', 'user_id', 'disposisi_id');
    }

    // hasrole diambil dari model role
    public function hasRole($role)
    {
        return $this->role->nama_role == $role;
    }

    public function arsips()
    {
        return $this->hasMany(Arsip::class)->onDelete('cascade');
    }

    public function disposisis()
    {
        return $this->hasMany(Disposisi::class)->onDelete('cascade');
    }
}
