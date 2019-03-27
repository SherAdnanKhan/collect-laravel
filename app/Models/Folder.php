<?php

namespace App\Models;

use App\Contracts\EventLoggable;
use App\Contracts\UserAccessible;
use App\Models\File;
use App\Models\User;
use App\Traits\EventLogged;
use App\Traits\OrderScopes;
use App\Traits\UserAccesses;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Folders contain files and folders.
 */
class Folder extends Model implements UserAccessible, EventLoggable
{
    use EventLogged;
    use UserAccesses;
    use SoftDeletes;
    use OrderScopes;

    protected $fillable = [
        'user_id', 'project_id', 'folder_id', 'name', 'depth'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get an array of paths with which make up
     * the path to this folder.
     *
     * @return array
     */
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
     * We'll override the type we're using to determine
     * user permissions. We will treat folders as files.
     *
     * @return string
     */
    public function getTypeName(): string
    {
        return 'file';
    }
}
