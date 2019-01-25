<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'studio_type',
        'label',
        'job_role',
        'genre',
        'workload',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public $timestamps = false;

    /**
     * Get the owning user of this model.
     *
     * @return Collection
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
