<?php

namespace App\Models;

use App\Models\Venue;
use App\Models\Credit;
use App\Models\Project;
use App\Models\Recording;
use App\Models\SessionCode;
use App\Models\SessionType;
use App\Traits\EventLogged;
use App\Traits\HasComments;
use App\Traits\OrderScopes;
use App\Traits\UserAccesses;
use ScoutElastic\Searchable;
use App\Contracts\Creditable;
use App\Contracts\Commentable;
use App\Contracts\EventLoggable;
use App\Contracts\UserAccessible;
use Illuminate\Database\Eloquent\Model;
use App\ElasticSearch\SessionSearchRule;
use Illuminate\Database\Eloquent\Builder;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\ElasticSearch\SessionsIndexConfigurator;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Util\BuilderQueries\CollaboratorRecordingAccess;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Session extends Model implements UserAccessible, EventLoggable, Creditable, Commentable
{
    use HasComments;
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
        SessionSearchRule::class
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
                'type' => 'date',
            ],
            'ended_at' => [
                'type' => 'date',
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
        $arr = array_only($this->toArray(), ['id', 'name', 'project_id']);
        $arr['started_at'] = $this->started_at->toIso8601String();
        $arr['ended_at'] = $this->ended_at->toIso8601String();
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

    /**
     * The session codes for this session.
     *
     * @return HasMany
     */
    public function sessionCodes(): HasMany
    {
        return $this->hasMany(SessionCode::class);
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
            return $q->whereHas('project', function ($q) use ($user) {
                return $q->whereHas('recordings', function($q) use ($user) {
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
            return $q->whereHas('project', function ($q) use ($user) {
                return $q->whereHas('recordings', function($q) use ($user) {
                    return (new CollaboratorRecordingAccess($q, $user))->getQuery();
                });
            });
        });

        return $this->wrapUserRelationCheck($user, $query);
    }
}
