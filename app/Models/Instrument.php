<?php

namespace App\Models;

use App\Models\Collaborators;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'category', 'ddex_key'
    ];

    /**
     * Disable timestamps.
     *
     * @var boolean
     */
    public $timestamps = false;
}
