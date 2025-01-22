<?php

declare(strict_types=1);

namespace App\Module\File\Handlers\Integration;

use App\Module\File\Commands\Integration\IntegrationCreateFileCommand;
use App\Module\File\Contracts\Queries\FileQuery;
use App\Module\File\Contracts\Repositories\CreateFileRepository;
use App\Module\File\Models\File;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class IntegrationCreateFileHandler
{
    public function __construct(
        private readonly CreateFileRepository $repository,
        private readonly FileQuery $query,
    ) {
    }

    public function handle(IntegrationCreateFileCommand $command): void
    {
        if ($this->fileExist($command->DTO->path)) {
            return;
        }

        $file                = new File();
        $file->path          = $command->DTO->path;
        $file->type          = $command->DTO->type;
        $file->original_name = $command->DTO->originalName;
        $file->client_id     = $command->DTO->clientId;
        $file->client_type   = $command->DTO->clientType;
        $file->user_id       = $command->DTO->userId;
        $file->uuid_hash     = $command->DTO->uuidHash;

        $this->repository->create($file);
    }

    private function fileExist(string $path): bool
    {
        try {
            $this->query->findByPath($path);

            return true;
        } catch (ModelNotFoundException) {
            return false;
        }
    }
}
