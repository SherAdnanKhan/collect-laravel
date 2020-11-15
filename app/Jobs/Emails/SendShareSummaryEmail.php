<?php

namespace App\Jobs\Emails;

use App\Mail\ShareSummary;
use App\Models\Share;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendShareSummaryEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $expiredShares = Share::whereRaw("DATE_FORMAT(expires_at, '%Y-%m-%d %H:%i:00') = ?", [Carbon::now()->second(00)->format('Y-m-d H:i:s')])
                            ->with('users')
                            ->get();

        foreach ($expiredShares as $share) {
            Mail::to($share->user)->send(new ShareSummary($share));
        }
    }
}
