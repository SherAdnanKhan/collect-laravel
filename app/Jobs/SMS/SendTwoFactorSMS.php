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
        Log::debug(sprintf('Sending code: %s to phone: %s', $this->code, $this->phone));

        $client = resolve('Nexmo\Client');
        $message = 'VeVa Two-Factor Code: %s';
        $text = new Text($this->phone, config('services.nexmo.from'), sprintf($message, $this->code));
        $text->setClientRef('2fa-' . $this->phone)
            ->setClass(Text::CLASS_FLASH);

        $client->message()->send($text);
    }
}
