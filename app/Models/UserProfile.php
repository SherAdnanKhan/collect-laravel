<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public $timestamps = false;

    /**
     * Get the owning user of this model.
     *
     * @return Collection
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
