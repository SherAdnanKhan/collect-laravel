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
        'collaborator_id', 'type', 'level'
    ];

    const TYPES = [
        'file', 'recording', 'project', 'session', 'collaborator',
    ];

    const LEVELS = [
        'create', 'read', 'update', 'delete', 'download',
    ];

    const TYPES_WITH_LABELS = [
        'file'         => 'Files & Folders',
        'recording'    => 'Recordings',
        'project'      => 'Project',
        'session'      => 'Sessions',
        'collaborator' => 'Collaborators',
    ];

    const LEVELS_WITH_LABELS = [
        'create'   => 'Create',
        'read'     => 'Read',
        'update'   => 'Update',
        'delete'   => 'Delete',
        'download' => 'Download',
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
