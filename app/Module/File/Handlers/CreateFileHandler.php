<?php

declare(strict_types=1);

namespace App\Module\File\Handlers;

use App\Module\File\Commands\CreateFileCommand;
use App\Module\File\Commands\UploadFileToAwsCommand;
use App\Module\File\Contracts\Repositories\CreateFileRepository;
use App\Module\File\Models\File;
use Illuminate\Contracts\Bus\Dispatcher;

final class CreateFileHandler
{
    public function __construct(
        private readonly CreateFileRepository $repository,
        private readonly Dispatcher $dispatcher,
    ) {
    }

    public function handle(CreateFileCommand $command): File
    {
        $path = $this->dispatcher->dispatch(new UploadFileToAwsCommand(
            $command->bucketName,
            $command->file
        ));

        $file                = new File();
        $file->path          = $path;
        $file->type          = $command->type;
        $file->original_name = $command->originalName;
        $file->client_id     = $command->clientId;
        $file->client_type   = $command->clientType;
        $file->user_id       = $command->userId;
        $file->setUuidHash();

        $this->repository->create($file);

        return $file;
    }
}
