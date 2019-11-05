<?php

namespace App\Observers;

use Illuminate\Support\Facades\Log;

class CreditableObserver
{
    /**
     * Handle the folder "saved" event.
     *
     * @param  \App\Contracts\Creditable  $resource
     * @return void
     */
    public function saved($resource)
    {
        foreach ($resource->credits as $credit) {
            $credit->save();
        }
    }
}
