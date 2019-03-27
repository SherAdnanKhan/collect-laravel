<?php

namespace App\Observers;

use App\Models\Comment;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Execution\Utils\Subscription;

class CommentObserver
{
    /**
     * Handle the folder "created" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        Subscription::broadcast('commentCreated', $comment);
    }
}
