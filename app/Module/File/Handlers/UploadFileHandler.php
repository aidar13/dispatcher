<?php

declare(strict_types=1);

namespace App\Module\File\Handlers;

use App\Module\File\Commands\CreateFileCommand;
use App\Module\File\Commands\UploadFileCommand;
use App\Module\File\Models\File;
use Illuminate\Contracts\Bus\Dispatcher;

final class UploadFileHandler
{
    public function __construct(private readonly Dispatcher $dispatcher)
    {
    }

    public function handle(UploadFileCommand $command): File
    {
        $clientType = File::getClientType($command->DTO->type);
        $path       = File::getPath($command->DTO->type);

        return $this->dispatcher->dispatch(new CreateFileCommand(
            $command->DTO->file,
            $command->DTO->type,
            $path,
            $command->DTO->file->getClientOriginalName(),
            $command->DTO->clientId,
            $clientType,
            $command->userId,
        ));
    }
}
