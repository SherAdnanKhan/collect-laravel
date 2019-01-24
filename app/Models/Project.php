<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function songs()
    {
        return $this->hasMany(Song::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
