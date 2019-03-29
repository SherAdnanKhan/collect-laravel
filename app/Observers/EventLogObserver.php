<?php

namespace App\Observers;

use App\Jobs\IncrementEventLogUnreadCounter;
use App\Models\EventLog;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class EventLogObserver
{
    /**
     * Handle the EventLog "created" event.
     *
     * @param  \App\EventLog  $eventLog
     * @return void
     */
    public function created(EventLog $eventLog)
    {
        IncrementEventLogUnreadCounter::dispatch($eventLog);
    }
}
