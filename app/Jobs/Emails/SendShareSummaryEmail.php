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
use Illuminate\Support\Facades\Storage;

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
        $expiredShares = Share::where('expires_at', Carbon::today()->subDay())
                            ->where('status',Share::STATUS_LIVE)
                            ->with('users')
                            ->get();

        foreach ($expiredShares as $share) {

            $share->status = Share::STATUS_EXPIRED;
            $share->save();
            Storage::disk('s3')->delete($share->path);
            Mail::to($share->user)->send(new ShareSummary($share));
        }
    }
}
