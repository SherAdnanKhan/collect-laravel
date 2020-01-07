<?php

namespace App\Models;

use App\Models\User;
use App\Models\Folder;
use App\Models\Project;
use App\Traits\HasComments;
use App\Traits\OrderScopes;
use App\Scopes\VisibleScope;
use App\Traits\UserAccesses;
use ScoutElastic\Searchable;
use App\Contracts\Commentable;
use App\Contracts\UserAccessible;
use App\ElasticSearch\NameSearchRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Util\BuilderQueries\ProjectAccess;
use App\ElasticSearch\FilesIndexConfigurator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Util\BuilderQueries\CollaboratorRecordingAccess;

/**
 * Represent a file that has been uploaded by a user into the system.
 */
class File extends Model implements UserAccessible, Commentable
{
    use HasComments;
    use UserAccesses;
    use SoftDeletes;
    use OrderScopes;
    use Searchable;

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETE = 'complete';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'project_id', 'folder_id', 'name', 'type', 'path', 'transcoded_path',
        'bitrate', 'bitdepth', 'size', 'samplerate', 'duration', 'numchans', 'status',
        'hidden', 'aliased_folder_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $touches = ['project'];

    protected $indexConfigurator = FilesIndexConfigurator::class;

    protected $searchRules = [
        NameSearchRule::class
    ];

    // Here you can specify a mapping for a model fields.
    protected $mapping = [
        'properties' => [
            'id' => [
                'type' => 'integer',
            ],
            'project_id' => [
                'type' => 'integer',
                // 'fields' => [
                //     'raw' => [
                //         'type' => 'keyword',
                //     ]
                // ]
            ],
            'user_id' => [
                'type' => 'integer',
                // 'fields' => [
                //     'raw' => [
                //         'type' => 'keyword',
                //     ]
                // ]
            ],
            'artist' => [
                'type' => 'text',
                // 'fields' => [
                //     'raw' => [
                //         'type' => 'keyword',
                //     ]
                // ]
            ],
            'name' => [
                'type' => 'text',
                // 'fields' => [
                //     'raw' => [
                //         'type' => 'keyword',
                //     ]
                // ]
            ],
        ]
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new VisibleScope);
    }

    /**
     * Get whether this file is previable
     *
     *
     * @return BelongsTo
     */
    public function getIsPreviewableAttribute()
    {
        return !empty($this->attributes['transcoded_path']) || $this->attributes['type'] == 'png' || $this->attributes['type'] == 'jpg' || $this->attributes['type'] == 'jepg';
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
     * Get the folder of which this file is an alias
     *
     * @return BelongsTo
     */
    public function aliasFolder(): BelongsTo
    {
        return $this->belongsTo(Folder::class, 'aliased_folder_id');
    }

    /**
     * Is the file an alias for a folder
     * which we want to treat as a file?

     * @param  Builder $query
     * @return Builder
     */
    public function scopeIsFolderAlias(Builder $query): Builder
    {
        return $query->whereNotNull('aliased_folder_id');
    }

    /**
     * Provide a default user viewable scope which will by default
     * filter out models where the user doesn't have read permissions on it's
     * related project using the type of the resource.
     *
     * @param  Builder $query
     * @param  array   $data
     * @return Builder
     */
    public function scopeUserViewable(Builder $query, $data = []): Builder
    {
        $user = $this->getUser($data);

        $query = $query->where(function($q) use ($user) {
            return (new ProjectAccess($q, $user, [$this->getTypeName()], ['read']))->getQuery();
        })->orWhere(function($q) use ($user) {
            return $q->whereHas('folder', function ($q) use ($user) {
                return $q->whereHas('recording', function($q) use ($user) {
                    return (new CollaboratorRecordingAccess($q, $user))->getQuery();
                });
            });
        });

        return $this->wrapUserRelationCheck($user, $query);
    }

    /**
     * Provide a default user viewable scope which will by default
     * filter out models where the user doesn't have read permissions on it's
     * related project using the type of the resource.
     *
     * @param  Builder $query
     * @param  array   $data
     * @return Builder
     */
    public function scopeUserUpdatable(Builder $query, $data = []): Builder
    {
        $user = $this->getUser($data);

        $query = $query->where(function($q) use ($user) {
            return (new ProjectAccess($q, $user, [$this->getTypeName()], ['update']))->getQuery();
        })->orWhere(function($q) use ($user) {
            return $q->whereHas('folder', function ($q) use ($user) {
                return $q->whereHas('recording', function($q) use ($user) {
                    return (new CollaboratorRecordingAccess($q, $user))->getQuery();
                });
            });
        });

        return $this->wrapUserRelationCheck($user, $query);
    }
}
