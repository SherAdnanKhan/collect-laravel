<?php

namespace App\Models;

use App\Contracts\Creditable;
use App\Contracts\EventLoggable;
use App\Contracts\UserAccessible;
use App\ElasticSearch\RecordingsIndexConfigurator;
use App\Models\Credit;
use App\Models\CreditRole;
use App\Models\Collaborator;
use App\Models\Party;
use App\Models\Project;
use App\Models\RecordingType;
use App\Models\Session;
use App\Models\Song;
use App\Models\SongRecording;
use App\Models\User;
use App\Models\VersionType;
use App\Traits\EventLogged;
use App\Traits\OrderScopes;
use App\Traits\UserAccesses;
use App\Util\BuilderQueries\CollaboratorRecordingAccess;
use App\Util\BuilderQueries\ProjectAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use ScoutElastic\Searchable;

class Recording extends Model implements UserAccessible, EventLoggable, Creditable
{
    use UserAccesses;
    use OrderScopes;
    use EventLogged;
    use SoftDeletes;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'party_id', 'party_role_id', 'song_id', 'name', 'recording_type_id', 'description',
        'isrc', 'subtitle', 'version', 'recorded_on', 'mixed_on', 'duration',
        'language_id', 'key_signature', 'time_signature', 'tempo', 'recording_type_user_defined_value', 'party_role_user_defined_value'
    ];

    protected $casts = [
        'recorded_on' => 'date',
        'mixed_on'    => 'date'
    ];

    /**
     * When updating this, we'll mark these relationships
     * as updated too.
     *
     * @var array
     */
    protected $touches = ['sessions', 'project'];

    protected $indexConfigurator = RecordingsIndexConfigurator::class;

    protected $searchRules = [
        // NameSearchRule::class
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
            'subtitle' => [
                'type' => 'text',
                // 'fields' => [
                //     'raw' => [
                //         'type' => 'keyword',
                //     ]
                // ]
            ],
            'description' => [
                'type' => 'text',
                // 'fields' => [
                //     'raw' => [
                //         'type' => 'keyword',
                //     ]
                // ]
            ]
        ]
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    // public function toSearchableArray()
    // {
    //     return [
    //         'id' => $this->attributes['id'],
    //         'project_id' => $this->attributes['project_id'],
    //         'user_id' => $this->attributes['user_id'],
    //         'name' => $this->attributes['name'] . $this->attributes['project_id']
    //     ];
    // }


    /**
     * Get the collaborator users for this project.
     *
     * @return BelongsToMany
     */
    public function collaborators(): BelongsToMany
    {
        return $this->belongsToMany(Collaborator::class, 'collaborators_to_recordings');
    }

    /**
     * Get the type that this recording is associated to.
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(RecordingType::class, 'recording_type_id');
    }

    /**
     * Get the project that this recording is associated to.
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the Party (main artist) that this recording is associated to.
     *
     * @return BelongsTo
     */
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    /**
     * Get the role of the party (main artist)
     *
     * @return BelongsTo
     */
    public function partyRole(): BelongsTo
    {
        return $this->belongsTo(CreditRole::class, 'party_role_id');
    }

    /**
     * Get the song that this recording is associated to.
     *
     * @return BelongsTo
     */
    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
    }

    /**
     * Get the sessions associated to this session.
     *
     * @return BelongsToMany
     */
    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(Session::class, 'sessions_to_recordings');
    }

    /**
     * Get where this recording has been favoured.
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
     * The language for the recording.
     *
     * @return BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * Specify the scope for how a user is able to see a recording.
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
        });

        // ->orWhere(function($q) use ($user) {
        //     return (new CollaboratorRecordingAccess($q, $user))->getQuery();
        // });

        return $this->wrapUserRelationCheck($user, $query);
    }

    public function getContributorRoleTypes($version = '1.1'): array
    {
        return ['NewStudioRole', 'CreativeContributorRole'];
    }

    public function getContributorReferenceKey($version = '1.1'): string
    {
        if ($version == '1.0') {
            return 'SoundRecordingContributorReference';
        }

        return 'ContributorPartyReference';
    }
}
