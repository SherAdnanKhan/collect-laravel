<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShareFile extends Model
{
    protected $fillable = ['share_id', 'file_id', 'folder_id'];

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
     * Get the owning file of this model.
     *
     * @return BelongsTo
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
