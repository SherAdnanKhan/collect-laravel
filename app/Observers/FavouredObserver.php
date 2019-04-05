<?php

namespace App\Observers;

use App\Models\File;
use App\Models\UserFavourite;
use Illuminate\Database\Eloquent\Model;

/**
 * This observer is responsible for creating event logs "activities"
 * when actions happen on resources which are EventLoggable
 */
class FavouredObserver
{
    /**
     * Handle the deleted event for an observed model.
     *
     * @param  EventLoggable $model
     * @return void
     */
    public function deleted(Model $model)
    {
        if ($model instanceof File) {
            $type = 'file';
        } else {
            $type = $model->getType();
        }
        UserFavourite::where('resource_type', $type)
                     ->where('resource_id', $model->id)
                     ->delete();
    }
}
