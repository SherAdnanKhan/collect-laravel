<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateDownloadZip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $userId;
    private $filesToDownload = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $filesToDownload)
    {
        $this->userId = $userId;
        $this->filesToDownload = $filesToDownload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $config = config('queue.connections.sqs');

        $client = new \Aws\Sqs\SqsClient([
            'region' => $config['region'],
            'version' => 'latest',
            'credentials' => [
                'key'    => $config['key'],
                'secret' => $config['secret'],
            ]
        ]);

        $params = [
            'MessageGroupId' => $this->job->getJobId(),
            'MessageDeduplicationId' => $this->job->getJobId(),
            'MessageBody' => json_encode([
                'userId' => $this->userId,
                'files' => $this->filesToDownload,
            ]),
            'QueueUrl' => $config['prefix'] . '/' . $config['jobs']['downloads']
        ];

        try {
            $result = $client->sendMessage($params);
        } catch (AwsException $e) {
            report($e);
        }
    }
}
