<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Represent a non-user image which is displayed throughout
 * the platform in various locations, such as the login page.
 */
class PlatformImage extends Model
{
    /**
     * Create a magic attribute which returns the full path
     * to the platform image.
     *
     * @return string
     */
    public function getFullPathAttribute(): string
    {
        return Storage::disk(config('filesystems.public'))->url($this->attributes['path']);
    }
}
