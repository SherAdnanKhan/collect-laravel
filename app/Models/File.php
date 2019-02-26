<?php

namespace App\Models;

use App\Models\Folder;
use App\Models\Project;
use App\Models\User;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Represent a file that has been uploaded by a user into the system.
 */
class File extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'project_id', 'folder_id', 'name', 'type',
        'path', 'transcoded_path',
        'bitrate', 'bitdepth', 'size',
        'status',
    ];

    /**
     * Get whether this file is previable
     *
     *
     * @return BelongsTo
     */
    public function getIsPreviewableAttribute()
    {
        return !empty($this->attributes['transcoded_path']);
    }

    /**
     * The project the file belongs to.
     *
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * The folder this file is in.
     *
     * @return BelongsTo
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    /**
     * Get the owning user of this file.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the files favourite row.
     *
     * @return MorphMany
     */
    public function favourites(): MorphMany
    {
        return $this->morphMany(UserFavourite::class, 'favoured');
    }

    /**
     * A scope to filter files with which the current user has
     * access to view, either by ownership or read access on
     * the project that this file is in.
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeUserViewable(Builder $query): Builder
    {
        $user = auth()->user();

        // Check to see if the current user owns or
        // has read access as a collaborator on the project
        // with which this file is on.
        return (new ProjectAccess($query, $user, ['read']))
            ->getQuery()
            ->orWhere('user_id', $user->getAuthIdentifier());
    }
}
