<?php

namespace App\Models;

use App\Models\File;
use App\Models\Song;
use App\Models\Party;
use App\Models\Venue;
use App\Models\Folder;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Session;
use App\Models\EventLog;
use App\Models\UserProfile;
use App\Models\Subscription;
use App\Models\Collaborators;
use App\Models\UserFavourite;
use Laravel\Cashier\Billable;
use App\Models\UserPluginCode;
use App\Models\UserTwoFactorToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Jobs\Emails\SendWelcomeEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Jobs\Emails\SendVerificationEmail;
use App\Jobs\Emails\SendUserPasswordResetEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Jobs\Emails\SendNewSubscriptionProPlanEmail;
use App\Jobs\Emails\SendNewSubscriptionProUnlimitedPlanEmail;
use App\Jobs\Emails\SendNewSubscriptionLitePlanEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Jobs\Emails\SendNewSubscriptionIndividualPlanEmail;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

class User extends Authenticatable implements JWTSubject, CanResetPassword
{
    use Billable;
    use Notifiable;
    use CanResetPasswordTrait;

    const SUBSCRIPTION_NAME = 'main';
    const DEFAULT_SUBSCRIPTION_PLAN = 'free';

    const PLAN_FREE = 'free';
    const PLAN_INDIVIDUAL = 'individual';
    const PLAN_EDUCATION = 'education';
    const PLAN_PRO = 'pro';
    const PLAN_PRO_UNLIMITED = 'prounlimited';

    const PLANS = [
        self::PLAN_FREE,
        self::PLAN_INDIVIDUAL,
        self::PLAN_EDUCATION,
        self::PLAN_PRO,
        self::PLAN_PRO_UNLIMITED,
    ];

    const PLAN_STORAGE_LIMITS = [
        self::PLAN_FREE          => 2 * 1024 * 1024 * 1024, // 2GB
        self::PLAN_INDIVIDUAL    => 1024 * 1024 * 1024 * 1024, // 1TB
        self::PLAN_EDUCATION     => false, // unlimited
        self::PLAN_PRO           => 1024 * 1024 * 1024 * 1024 * 3, // 3TB
        self::PLAN_PRO_UNLIMITED => false, // unlimited
    ];

    const PLAN_STORAGE_LIMITS_PRETTY = [
        self::PLAN_FREE          => '2GB',
        self::PLAN_INDIVIDUAL    => '1TB',
        self::PLAN_EDUCATION     => 'Unlimited',
        self::PLAN_PRO           => '3TB',
        self::PLAN_PRO_UNLIMITED => 'Unlimited',
    ];

    const PLAN_NAMES = [
        self::PLAN_FREE          => 'Lite',
        self::PLAN_INDIVIDUAL    => 'Individual',
        self::PLAN_EDUCATION     => 'Education',
        self::PLAN_PRO           => 'Pro',
        self::PLAN_PRO_UNLIMITED => 'Pro Unlimited',
    ];

    protected $guard = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email',
        'password', 'phone', 'two_factor_enabled',
        'total_storage_used'
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
        return $this->hasMany(File::class)->whereNull('project_id');
    }

    /**
     * Get the files belonging to this user.
     *
     * @return HasMany
     */
    public function filesNoScope(): HasMany
    {
        return $this->hasMany(File::class)->whereNull('project_id')->withoutGlobalScopes();
    }

    /**
     * Get the files belonging to this user.
     *
     * @return HasMany
     */
    public function recentFiles(): HasMany
    {
        return $this->hasMany(File::class)->whereNull('project_id')->orderBy('created_at', 'desc')->limit(6);
    }

    /**
     * Get the files belonging to this user.
     *
     * @return HasMany
     */
    public function folders(): HasMany
    {
        return $this->hasMany(Folder::class)->whereNull('project_id');
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
     * @param int $additional_storage_used
     * @return bool
     */
    public function hasStorageSpaceAvailable(int $additional_storage_used = 0): bool
    {
        $this->load('subscriptions');
        $subscription = $this->subscription(self::SUBSCRIPTION_NAME);

        if (!$subscription) {
            return false;
        }

        $limit = false;
        $plan = $subscription->stripe_plan;

        if (array_key_exists($plan, self::PLAN_STORAGE_LIMITS)) {
            $limit = array_get(self::PLAN_STORAGE_LIMITS, $plan);
        }

        return $limit === false || ($this->total_storage_used + $additional_storage_used) < $limit;
    }

    /**
     * Return whether the user is within 5% of
     * their storage limit
     *
     * @return bool
     */
    public function isCloseToStorageLimit(): bool
    {
        $this->load('subscriptions');
        $subscription = $this->subscription(self::SUBSCRIPTION_NAME);

        if (!$subscription) {
            return true;
        }

        $plan = $subscription->stripe_plan;

        if (!array_key_exists($plan, self::PLAN_STORAGE_LIMITS)) {
            return false;
        }

        $limit = array_get(self::PLAN_STORAGE_LIMITS, $plan);
        if ($limit === false) {
            return false;
        }

        return $this->total_storage_used_percentage >= 95;
    }

    /**
     * Does the user require 2 factor authentication?
     *
     * @return bool
     */
    public function getTotalStorageUsedPercentageAttribute(): int
    {
        $subscription = $this->subscription(self::SUBSCRIPTION_NAME);

        if (!$subscription) {
            return 0;
        }

        $limit = false;
        $plan = $subscription->stripe_plan;

        if (array_key_exists($plan, self::PLAN_STORAGE_LIMITS)) {
            $limit = array_get(self::PLAN_STORAGE_LIMITS, $plan);
        }

        if ($limit === false) {
            return 0;
        }

        return min(round(($this->total_storage_used / $limit) * 100), 100);
    }

    /**
     * Does the user require 2 factor authentication?
     *
     * @return bool
     */
    public function getTotalStorageUsedPrettyAttribute(): string
    {
        $subscription = $this->subscription(self::SUBSCRIPTION_NAME);

        if (!$subscription) {
            return 0;
        }

        if ($this->total_storage_used == 0) {
            return '0B';
        }

        $precision = 2;
        $base = log($this->total_storage_used, 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];

        return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
    }

    /**
     * A user only has collaborator access if they're on the pro or
     * education plan.
     *
     * @return bool
     */
    public function hasCollaboratorAccess(): bool
    {
        return $this->subscribedToPlan([self::PLAN_EDUCATION, self::PLAN_PRO, self::PLAN_PRO_UNLIMITED], self::SUBSCRIPTION_NAME);
    }

    /**
     * Does the user require 2 factor authentication?
     *
     * @return bool
     */
    public function requiresTwoFactor(): bool
    {
        return (bool) $this->two_factor_enabled && !is_null($this->phone);
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

    /**
     * Send the new subscription emails.
     *
     * @return void
     */
    public function sendNewSubscriptionEmail($subscription = null)
    {
        if (is_null($subscription)) {
            // send the relevant subscription notification.
            $subscription = $this->subscription(self::SUBSCRIPTION_NAME);
        }

        if (!$subscription) {
            return;
        }

        $plan = $subscription->stripe_plan;

        if ($plan === self::PLAN_FREE) {
            SendNewSubscriptionLitePlanEmail::dispatch($this);
        } else if ($plan === self::PLAN_INDIVIDUAL) {
            SendNewSubscriptionIndividualPlanEmail::dispatch($this);
        } else if ($plan === self::PLAN_PRO) {
            SendNewSubscriptionProPlanEmail::dispatch($this);
        } else if ($plan === self::PLAN_PRO_UNLIMITED) {
            SendNewSubscriptionProUnlimitedPlanEmail::dispatch($this);
        }
    }

    /**
     * Get the path to this users root files
     *
     * @return string
     */
    public function getUploadFolderPath()
    {
        return md5($this->attributes['id'] . $this->attributes['created_at']);
    }
}
