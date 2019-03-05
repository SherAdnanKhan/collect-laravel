<?php

namespace App\Controllers\Webhooks;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

/**
 * Handle stripe webhook requests.
 */
class StripeController extends CashierController
{
    // Override methods from the cashier controller when we want to do
    // something different.
}
