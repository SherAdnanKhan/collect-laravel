<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;

class UserStorageLimitReached extends Mailable
{

    /**
     * The user instance
     *
     * @var User
     */
    protected $user;


    /**
     * Create a new message instance.
     *
     * @param User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = config('app.frontend_url') . '/subscription';

        return $this->view('emails.users.storage-limit-reached')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('VEVA Collect Storage Limit Alert')
            ->with([
                'name' => $this->user->first_name,
                'link' => $url,
            ]);
    }
}
