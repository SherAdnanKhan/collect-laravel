<?php

namespace App\Models;

use App\Contracts\UserAccessible;
use App\ElasticSearch\FilesIndexConfigurator;
use App\Models\Folder;
use App\Models\Project;
use App\Models\User;
use App\Traits\OrderScopes;
use App\Traits\UserAccesses;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use ScoutElastic\Searchable;
use App\ElasticSearch\NameSearchRule;

/**
 * Represent a file that has been uploaded by a user into the system.
 */
class File extends Model implements UserAccessible
{
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
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

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
}
