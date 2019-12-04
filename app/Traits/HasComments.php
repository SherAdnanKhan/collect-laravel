<?php

namespace App\Traits;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasComments
{
    public function getCommentCountAttribute(): int
    {
        return $this->comments()->count();
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'resource');
    }
}
