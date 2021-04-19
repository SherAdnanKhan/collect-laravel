<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShareUserDownload extends Model
{
    protected $fillable = ['share_user_id'];

    /**
     * Get the owning file of this model.
     *
     * @return BelongsTo
     */
    public function shareUser(): BelongsTo
    {
        return $this->belongsTo(ShareUser::class);
    }
}
