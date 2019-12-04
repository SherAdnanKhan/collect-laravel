<?php

namespace App\Jobs\SMS;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Nexmo\Message\Text;

/**
 * Calculates the users total storage used in bytes.
 */
class SendTwoFactorSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phone;
    protected $code;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phone, $code)
    {
        $this->phone = $phone;
        $this->code = $code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $from = config('services.nexmo.from');
        Log::debug(sprintf('Sending code: %s to phone: %s from: %s', $this->code, $this->phone, $from));

        $client = resolve('Nexmo\Client');
        $message = 'Your 2FA Code for VEVA Collect is: %s';
        $text = new Text($this->phone, $from, sprintf($message, $this->code));
        // $text->setClientRef('2fa-' . $this->phone);
        //     ->setClass(Text::CLASS_FLASH);

        $client->message()->send($text);
    }
}
