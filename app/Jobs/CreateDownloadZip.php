<?php

namespace App\Jobs;

use App\Models\DownloadJob;
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
    private $zipName;
    private $projectId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $filesToDownload, $zipName)
    {
        $this->userId = $userId;
        $this->filesToDownload = collect($filesToDownload)->map(function ($file) {
            return $file->only(['id', 'status', 'depth', 'aliased_folder_id']);
        });
        $this->zipName = $zipName;
        $this->projectId = $filesToDownload[0]->project_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $config = config('queue.connections.sqs');

        $download_job = DownloadJob::create([
            'user_id' => $this->userId,
            'project_id' => $this->projectId,
        ]);

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
                'downloadJobId' => $download_job->id,
                'userId' => $this->userId,
                'files' => $this->filesToDownload,
                'zipName' => $this->zipName
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
