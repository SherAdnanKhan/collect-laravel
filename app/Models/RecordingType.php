<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordingType extends Model
{
    protected $fillable = ['name', 'ddex_key', 'user_defined'];
}
