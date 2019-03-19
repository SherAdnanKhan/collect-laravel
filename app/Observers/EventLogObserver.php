<?php

namespace App\Observers;

use App\Contracts\EventLoggable;
use App\Models\EventLog;

/**
 * This observer is responsible for creating event logs "activities"
 * when actions happen on resources.
 */
class EventLogObserver
{
    /**
     * Handle the created event for an observed model.
     *
     * @param  EventLoggable $model
     * @return void
     */
    public function created(EventLoggable $model)
    {
        $this->logEvent($model, 'create', sprintf(
            '%s has created %s: %s',
            $this->user()->name,
            $model->getTypeLabel(),
            $model->getIdentifier()
        ));
    }

    /**
     * Handle the updated event for an observed model.
     *
     * @param  EventLoggable $model
     * @return void
     */
    public function updated(EventLoggable $model)
    {
        $this->logEvent($model, 'update', sprintf(
            '%s has updated %s: %s',
            $this->user()->name,
            $model->getTypeLabel(),
            $model->getIdentifier()
        ));
    }

    /**
     * Handle the deleted event for an observed model.
     *
     * @param  EventLoggable $model
     * @return void
     */
    public function deleted(EventLoggable $model)
    {
        $this->logEvent($model, 'delete', sprintf(
            '%s has removed %s: %s',
            $this->user()->name,
            $model->getTypeLabel(),
            $model->getIdentifier()
        ));
    }

    private function logEvent(EventLoggable $model, string $action, string $message)
    {
        $eventLog = new EventLog();
        $projectId = !is_null($model->getProject()) ? $model->getProject()->getKey() : null;

        $eventLog->fill([
            'user_id'       => $this->user()->getAuthIdentifier(),
            'project_id'    => $projectId,
            'resource_id'   => $model->getKey(),
            'resource_type' => $model->getType(),
            'message'       => $message,
            'action'        => $action,
        ])->save();
    }

    /**
     * The user to attribute the event to.
     *
     * @return mixed
     */
    private function user()
    {
        return auth()->user();
    }
}
