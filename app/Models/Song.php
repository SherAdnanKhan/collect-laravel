<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = [
        'user_id', 'iswc', 'title', 'type', 'subtitle',
        'genre', 'artist'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
