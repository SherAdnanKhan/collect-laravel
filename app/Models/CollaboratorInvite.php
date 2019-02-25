<?php

namespace App\Models;

use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CollaboratorInvite extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'email', 'token',
    ];

    /**
     * The project this collaborator belongs to.
     *
     * @return User
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
