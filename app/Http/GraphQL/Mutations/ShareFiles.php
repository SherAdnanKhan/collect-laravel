<?php

namespace App\Http\GraphQL\Mutations;

use App\Jobs\CreateShareZip;
use App\Models\File;
use App\Models\Folder;
use App\Scopes\VisibleScope;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ShareFiles
{
    private $filesToShare = [];
    private $emailsToShare = [];
    private $message;
    private $expiry;
    private $password;

    /**
     * @param $rootValue
     * @param array $args
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext|null $context
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo
     * @return array
     * @throws \Exception
     */
    public function resolve($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $user = auth()->user();
        $input = $args['input'];

        $this->getfilesToShare($user, $input['files']);
        $this->emailsToShare = collect($input['users'])->pluck('email');
        $this->expiry = (isset($input['expiry']) && !empty($input['expiry'])) ? $input['expiry'] : null;
        $this->password = $input['password'];
        $this->message = $input['message'];

        if (!isset($this->filesToShare[0]) || !isset($this->emailsToShare[0]) || !$this->isValidExpiry($this->expiry)) {
            return [
                'success' => false
            ];
        }

        $zipName = $this->getZipName($user, $input['files']);

        CreateShareZip::dispatch($user->id, $this->filesToShare, $this->emailsToShare, $this->message, $this->expiry, $this->password, $zipName);

        return [
            'success' => true
        ];
    }

    private function getfilesToShare($user, $files)
    {
        $folders = [];
        $filesToShare = [];

        foreach ($files as $file) {
            if ($file['type'] === 'folder') {
                $this->getFolderFiles(Folder::find($file['id']), 1);
                continue;
            }

            $file = File::select('id', 'type', 'status', 'aliased_folder_id', 'path')->where('id', $file['id'])->userViewable(['user' => $user])->first();

            if (!$file || $file->status === File::STATUS_PENDING) {
                continue;
            }

            if ($file->isAlias()) {
                $aliasFolder = $file->aliasFolder()->withoutGlobalScope(VisibleScope::class)->first();

                if (is_null($aliasFolder)) {
                    throw new \Exception('File is alias but cannot find folder with id: ' . $aliasFolder->aliased_folder_id);
                }

                $this->getFolderFiles($aliasFolder, 1);
                continue;
            }

            $this->addFile($file);
        }
    }

    private function getFolderFiles(Folder $folder, $depth = 0)
    {
        $filesToShare = [];

        $files = $folder->files()->withoutGlobalScope(VisibleScope::class)->get();
        foreach ($files as $file) {
            if ($file->isAlias()) {
                $aliasFolder = $file->aliasFolder()->withoutGlobalScope(VisibleScope::class)->get();
                $this->getFolderFiles($aliasFolder->first(), $depth + 1);
                continue;
            }

            $this->addFile($file, $depth);
        }

        $folders = $folder->folders()->withoutGlobalScope(VisibleScope::class)->get();
        foreach ($folders as $folder) {
            $this->getFolderFiles($folder, $depth + 1);
        }
    }

    private function addFile($file, $depth = 0)
    {
        $file->depth = $depth;
        $this->filesToShare[] = $file;
    }

    private function isValidExpiry($expiry)
    {
        if (!isset($expiry)) {
            return true;
        }

        return Carbon::parse($expiry)->gte(Carbon::today());
    }

    private function getZipName($user, $files)
    {
        $firstFile = $files[0];
        if ($firstFile['type'] === 'folder') {
            $folder = Folder::select('id','folder_id')->where('id', $firstFile['id'])->userViewable(['user' => $user])->first();
            if (isset($folder->folder_id)) {
                $parentFolder = Folder::select('id','name')->where('id', $folder->folder_id)->userViewable(['user' => $user])->first();
                return $parentFolder->name;
            }
            return 'VEVA';
        }
        $file = File::select('id', 'folder_id')->where('id', $firstFile['id'])->userViewable(['user' => $user])->first();
        if (isset($file->folder_id)) {
            $folder = Folder::select('id','name')->where('id', $file->folder_id)->userViewable(['user' => $user])->first();
            return $folder->name;
        }
        return 'VEVA';
    }
}
