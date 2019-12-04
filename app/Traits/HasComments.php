<?php

namespace App\Traits;

trait HasComments
{
    public function getCommentCountAttribute(): int
    {
        return $this->comments()->count();
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
