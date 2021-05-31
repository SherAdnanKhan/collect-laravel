<?php

namespace App\Jobs;

use App\Models\Share;
use App\Models\ShareFile;
use App\Models\ShareUser;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreateShareZip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $userId;
    private $filesToShare = [];
    private $projectId;
    private $emailsToShare = [];
    private $message;
    private $expiry;
    private $password;
    private $zipName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $filesToShare, $emailsToShare, $message, $expiry, $password, $zipName)
    {
        $this->userId = $userId;
        $this->filesToShare = collect($filesToShare)->map(function ($file) {
            return $file->only(['id', 'type', 'status', 'depth', 'aliased_folder_id', 'folder_id']);
        });

        $this->projectId = $filesToShare[0]->project_id;
        $this->emailsToShare = $emailsToShare;
        $this->message = $message;
        $this->expiry = $expiry;
        $this->password = $password;
        $this->zipName = $zipName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $config = config('queue.connections.sqs');

        try {
            DB::beginTransaction();

            $share = Share::create([
                'user_id' => $this->userId,
                'project_id' => $this->projectId,
                'message' => $this->message,
                'password' => (!empty($this->password)) ? bcrypt($this->password) : null,
                'expires_at' => (isset($this->expiry)) ? Carbon::parse($this->expiry) : null
            ]);

            foreach ($this->filesToShare as $file) {
                ShareFile::create([
                    'share_id' => $share->id,
                    'file_id' => $file['id'],
                    'folder_id' => $file['folder_id']
                ]);
            }

            foreach ($this->emailsToShare as $email) {
                ShareUser::create([
                    'share_id' => $share->id,
                    'email' => $email,
                    'encrypted_email' => bin2hex($email)
                ]);
            }

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
                    'shareId' => $share->id,
                    'userId' => $this->userId,
                    'files' => $this->filesToShare,
                    'zipName' => $this->zipName
                ]),
                'QueueUrl' => $config['prefix'] . '/' . $config['jobs']['downloads']
            ];

            $client->sendMessage($params);

            DB::commit();
        } catch (AwsException $e) {
            DB::rollback();
            report($e);
        }
    }
}
