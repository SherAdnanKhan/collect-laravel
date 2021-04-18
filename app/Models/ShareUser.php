<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShareUser extends Model
{
    protected $fillable = ['share_id', 'email', 'encrypted_email', 'download_count', 'downloaded_last_at'];

    /**
     * Get the owning share of this model.
     *
     * @return BelongsTo
     */
    public function share(): BelongsTo
    {
        return $this->belongsTo(Share::class);
    }

    /**
     * Get the owning user of this model.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the owning downloads of this model.
     *
     * @return HasMany
     */
    public function downloads(): HasMany
    {
        return $this->hasMany(ShareUserDownload::class);
    }
}
