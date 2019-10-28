<?php

namespace App\Models;

use App\Contracts\Creditable;
use App\Contracts\EventLoggable;
use App\Contracts\UserAccessible;
use App\ElasticSearch\SessionsIndexConfigurator;
use App\Models\Credit;
use App\Models\Project;
use App\Models\Recording;
use App\Models\SessionType;
use App\Models\Venue;
use App\Traits\EventLogged;
use App\Traits\OrderScopes;
use App\Traits\UserAccesses;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use ScoutElastic\Searchable;
use App\ElasticSearch\NameSearchRule;
use Illuminate\Support\Facades\Log;

class Session extends Model implements UserAccessible, EventLoggable, Creditable
{
    use UserAccesses;
    use OrderScopes;
    use EventLogged;
    use SoftDeletes;
    use Searchable;

    protected $fillable = [
        'project_id', 'session_type_id', 'venue_id', 'name', 'description', 'started_at',
        'ended_at', 'union_session', 'analog_session', 'drop_frame', 'venue_room', 'bitdepth',
        'samplerate', 'timecode_type', 'timecode_frame_rate'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime'
    ];

    /**
     * When updating this, we'll mark these relationships
     * as updated too.
     *
     * @var array
     */
    protected $touches = ['project'];

    protected $indexConfigurator = SessionsIndexConfigurator::class;

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
            ],
            'name' => [
                'type' => 'text',
                // 'fields' => [
                //     'raw' => [
                //         'type' => 'keyword',
                //     ]
                // ]
            ],
            'started_at' => [
                'type' => 'text',
            ],
            'ended_at' => [
                'type' => 'text',
            ],
            'session_type' => [
                'type' => 'text',
            ],
            'venue' => [
                'type' => 'text',
            ],
        ]
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $arr = array_only($this->toArray(), ['id', 'name', 'started_at', 'ended_at', 'project_id']);
        $arr['session_type'] = $this->sessionType->name;
        $arr['venue'] = $this->venue->name;
        return $arr;
    }

    /**
     * The project who owns this song.
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * The venue who owns this song.
     *
     * @return BelongsTo
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * The type of the session
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(SessionType::class, 'session_type_id');
    }

    /**
     * The type of the session
     * @return BelongsTo
     */
    public function sessionType(): BelongsTo
    {
        return $this->belongsTo(SessionType::class, 'session_type_id');
    }

    /**
     * Get the recordings associated to this session.
     *
     * @return BelongsToMany
     */
    public function recordings(): BelongsToMany
    {
        return $this->belongsToMany(Recording::class, 'sessions_to_recordings');
    }

    /**
     * Where this session has been favourited/
     *
     * @return MorphMany
     */
    public function favourites(): MorphMany
    {
        return $this->morphMany(UserFavourite::class, 'favoured');
    }

    /**
     * Grab the credits/contributions directly on this resource.
     *
     * @return MorphMany
     */
    public function credits(): MorphMany
    {
        return $this->morphMany(Credit::class, 'contribution');
    }

    public function getContributorRoleTypes($version = '1.1'): array
    {
        if ($version == '1.0') {
            return ['NewStudioRole', 'CreativeContributorRole'];
        }

        return ['ResourceContributorRole'];
    }

    public function getContributorReferenceKey($version = '1.1'): string
    {
        return 'SessionContributorReference';
    }
}
