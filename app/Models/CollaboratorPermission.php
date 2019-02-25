<?php

namespace App\Models;

use App\Models\Collaborator;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CollaboratorPermission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'collaborator_id', 'level'
    ];

    /**
     * Get the collaborator.
     *
     * @return BelongsTo
     */
    public function collaborator(): BelongsTo
    {
        return $this->belongsTo(Collaborator::class);
    }
}
