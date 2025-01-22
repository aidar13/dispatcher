<?php

declare(strict_types=1);

namespace App\Module\File\Handlers;

use App\Module\File\Commands\DeleteFileCommand;
use App\Module\File\Contracts\Queries\FileQuery;
use App\Module\File\Contracts\Repositories\DeleteFileRepository;
use Illuminate\Support\Facades\Storage;

final class DeleteFileHandler
{
    public function __construct(
        private readonly DeleteFileRepository $repository,
        private readonly FileQuery $fileQuery
    ) {
    }

    public function handle(DeleteFileCommand $command): void
    {
        $file = $this->fileQuery->findById($command->id);

        if (Storage::disk('s3')->exists($file->path)) {
            Storage::disk('s3')->delete($file->path);
        }

        $this->repository->delete($file->uuid_hash);
    }
}
