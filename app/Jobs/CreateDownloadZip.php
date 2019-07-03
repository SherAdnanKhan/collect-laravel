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

    private $user_id;
    private $files_to_download = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $files_to_download)
    {
        $this->user_id = $user_id;
        $this->files_to_download = $files_to_download;
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
                'user_id' => $this->user_id,
                'files' => $this->files_to_download,
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
