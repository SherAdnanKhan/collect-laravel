<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new \App\Jobs\UpdateUserTotalStorageUsed)
                 ->everyMinute()
                 ->onOneServer();

        $schedule->job(new \App\Jobs\DeleteFiles)
                 ->hourly()
                 ->onOneServer();

        $schedule->job(new \App\Jobs\CheckUserTotalStorageUsed)
                 ->daily()
                 ->onOneServer();

        $schedule->job(new \App\Jobs\Emails\SendShareSummaryEmail)
            ->everyMinute()
            ->onOneServer();

        $schedule->job(new \App\Jobs\RefreshExpiredShareAWSUrls)
            ->everyMinute()
            ->onOneServer();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
