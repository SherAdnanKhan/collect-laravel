<?php

namespace App\Models;

use App\Jobs\Email\SendUserPasswordResetEmail;
use App\Jobs\Email\SendVerificationEmail;
use App\Jobs\Email\SendWelcomeEmail;
use App\Models\Collaborators;
use App\Models\Comment;
use App\Models\EventLog;
use App\Models\File;
use App\Models\Party;
use App\Models\Project;
use App\Models\Session;
use App\Models\Song;
use App\Models\Subscription;
use App\Models\UserFavourite;
use App\Models\UserPluginCode;
use App\Models\UserProfile;
use App\Models\UserTwoFactorToken;
use App\Models\Venue;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Billable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, CanResetPassword
{
    use Billable;
    use Notifiable;
    use CanResetPasswordTrait;

    const SUBSCRIPTION_NAME = 'main';
    const DEFAULT_SUBSCRIPTION_PLAN = 'free';

    const PLANS = [
        self::DEFAULT_SUBSCRIPTION_PLAN,
        'individual',
        'education',
        'pro',
    ];

    const PLAN_STORAGE_LIMITS = [
        'free'       => 2 * 1000 * 1000 * 1000, // 2GB
        'individual' => 1000 * 1000 * 1000 * 1000, // 1TB
        'education'  => false, // unlimited
        'pro'        => false, // unlimited
    ];

    protected $guard = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Allow access to a 'name' attribute for display purposes.
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }

    /**
     * When setting the password we'll Hash it.
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $hashInfo = Hash::info($password);

        // Make sure it's not already hashed.
        if (array_get($hashInfo, 'algo') == 0) {
            $this->attributes['password'] = Hash::make($password);
            return;
        }

        $this->attributes['password'] = $password;
    }

    /**
     * The profile associated to the user.
     *
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * The two factor tokens for the user.
     *
     * @return HasMany
     */
    public function tokens(): HasMany
    {
        return $this->hasMany(UserTwoFactorToken::class);
    }

    /**
     * The users favourite items in the system.
     *
     * @return HasMany
     */
    public function favourites(): HasMany
    {
        return $this->hasMany(UserFavourite::class);
    }

    /**
     * All of the users projects.
     *
     * @return HasMany
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the times this user has been a collaborator
     *
     * @return HasMany
     */
    public function collaborators(): HasMany
    {
        return $this->hasMany(Collaborator::class);
    }

    /**
     * Get the parties on this users accounts.
     *
     * @return HasMany
     */
    public function parties(): HasMany
    {
        return $this->hasMany(Party::class);
    }

    /**
     * Get the files belonging to this user.
     *
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    /**
     * Get the files belonging to this user.
     *
     * @return HasMany
     */
    public function songs(): HasMany
    {
        return $this->hasMany(Song::class);
    }

    /**
     * Get all of the users comments.
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get venues owned by a user.
     *
     * @return HasMany
     */
    public function venues(): HasMany
    {
        return $this->hasMany(Venue::class);
    }

    /**
     * Get the event log items that this user has caused.
     *
     * @return HasMany
     */
    public function eventLogs(): HasMany
    {
        return $this->hasMany(EventLog::class);
    }

    /**
     * Get the plugin codes for a user, for a specific session.
     *
     * @param  Session $session
     * @return HasMany
     */
    public function pluginCodes(Session $session): HasMany
    {
        return $this->hasMany(UserPluginCode::class)->where('session_id', $session->id);
    }

    /**
     * Get all of the subscriptions for the Stripe model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, $this->getForeignKey())->orderBy('created_at', 'desc');
    }

    /**
     * Return whether the user has storage space available
     *
     * @return bool
     */
    public function hasStorageSpaceAvailable(): bool
    {
        $plan = $this->subscriptions()->first();
        $limit = false;

        if (array_key_exists($plan, self::PLAN_STORAGE_LIMITS)) {
            $limit = array_get(self::PLAN_STORAGE_LIMITS, $plan);
        }

        return $this->total_storage_used < $limit || !$limit;
    }

    /**
     * A user only has collaborator access if they're on the pro or
     * education plan.
     *
     * @return bool
     */
    public function hasCollaboratorAccess(): bool
    {
        return $this->subscribedToPlan(['education', 'pro'], self::SUBSCRIPTION_NAME);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        SendUserPasswordResetEmail::dispatch($this, $token);
    }

    /**
     * Send the user a verification notification
     *
     * @return void
     */
    public function sendRegistrationVerificationNotification()
    {
        SendVerificationEmail::dispatch($this, $this->verification_token);
    }

    /**
     * Send the user a welcome notification
     *
     * @return void
     */
    public function sendWelcomeNotification()
    {
        SendWelcomeEmail::dispatch($this);
    }
}
