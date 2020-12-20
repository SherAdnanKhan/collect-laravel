<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\Share;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RefreshExpiredShareAWSUrls implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $config;
    private $client;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->config = config('queue.connections.sqs');
        $this->client = new \Aws\Sqs\SqsClient([
            'region' => $this->config['region'],
            'version' => 'latest',
            'credentials' => [
                'key'    => $this->config['key'],
                'secret' => $this->config['secret'],
            ]
        ]);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $awsExpiredShares = Share::whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d 00:00:00') = ?", [Carbon::now()->subDays(7)->hour(00)->minute(00)->second(00)->format('Y-m-d H:i:s')])
                                ->where('status', Share::STATUS_LIVE)
                                ->get();

        foreach ($awsExpiredShares as $share) {
            $filesToShare = $share->files->map(function ($shareFile) {
                $file = File::find($shareFile->file_id);
                return $file->only(['id', 'type', 'status', 'depth', 'aliased_folder_id', 'folder_id']);
            });

            $params = [
                'MessageGroupId' => $this->job->getJobId(),
                'MessageDeduplicationId' => $this->job->getJobId(),
                'MessageBody' => json_encode([
                    'shareJobId' => $share->id,
                    'userId' => $share->user->id,
                    'files' => $filesToShare,
                    'refresh' => true
                ]),
                'QueueUrl' => $this->config['prefix'] . '/' . $this->config['jobs']['downloads']
            ];

            $this->client->sendMessage($params);
        }
    }
}
