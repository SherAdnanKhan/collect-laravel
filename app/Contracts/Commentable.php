<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Commentable
{
    public function getCommentCountAttribute(): int;
    public function comments(): MorphMany;
}
