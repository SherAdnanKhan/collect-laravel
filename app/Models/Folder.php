<?php

namespace App\Models;

use App\Models\File;
use App\Models\User;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Folders contain files and folders.
 */
class Folder extends Model
{
    protected $fillable = [
        'user_id', 'project_id', 'folder_id', 'name', 'depth'
    ];

    public function getPathAttribute(): array
    {
        $path = [];

        $parent = self::find($this->attributes['folder_id']);
        while ($parent) {
            $path[] = [
                'id' => $parent->id,
                'name' => $parent->name
            ];
            $parent = self::find($parent->folder_id);
        }

        return array_reverse($path);
    }

    /**
     * Get the user that owns this folder.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent folder.
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Folder::class, 'folder_id');
    }

    /**
     * Get the project this folder is in.
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get all the files in this folder.
     *
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    /**
     * Get any child folders.
     *
     * @return HasMany
     */
    public function folders(): HasMany
    {
        return $this->hasMany(Folder::class);
    }

    /**
     * A scope to filter folders with which the current user has
     * access to view, either by ownership or read access on
     * the project that this file is in.
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeUserViewable(Builder $query): Builder
    {
        $user = auth()->user();

        // Check to see if the current user has read access as a
        // collaborator on the project with which this is on.
        return (new ProjectAccess($query, $user, ['read']))
            ->getQuery()
            ->orWhere('user_id', $user->getAuthIdentifier());
    }
}
