<?php

declare(strict_types=1);

namespace App\Module\File\Handlers;

use App\Module\File\Commands\DeleteFileWithoutAwsCommand;
use App\Module\File\Contracts\Repositories\DeleteFileRepository;

final class DeleteFileWithoutAwsHandler
{
    public function __construct(
        private readonly DeleteFileRepository $repository,
    ) {
    }

    public function handle(DeleteFileWithoutAwsCommand $command): void
    {
        $this->repository->delete($command->uuidHash);
    }
}
