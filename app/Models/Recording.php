<?php

namespace App\Models;

use App\Models\Collaborators;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Recording extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'name', 'type', 'description',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * Get the project that this recording is asociated to.
     *
     * @return Project
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
