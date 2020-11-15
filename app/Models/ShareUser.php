<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShareUser extends Model
{
    protected $fillable = ['share_id', 'email', 'encrypted_email', 'download_count', 'downloaded_last_at'];

    /**
     * Get the owning share of this model.
     *
     * @return Collection
     */
    public function share(): BelongsTo
    {
        return $this->belongsTo(Share::class);
    }

    /**
     * Get the owning file of this model.
     *
     * @return Collection
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
