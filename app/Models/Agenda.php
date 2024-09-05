<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agenda extends Model
{
    use HasFactory;
    protected $table = 'agenda';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->BelongsTo(User::class);
    }
    public function pimpinan()
    {
        return $this->belongsTo(User::class, 'dipimpin', 'id'); // Ensure 'pimpinan_id' is the correct foreign key in your database schema
    }
}
