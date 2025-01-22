<?php

declare(strict_types=1);

namespace App\Module\Inventory\Handlers\Integration;

use App\Module\Inventory\Commands\Integration\CreateWriteOffCommand;
use App\Module\Inventory\Contracts\Repositories\CreateWriteOffRepository;

final class CreateWriteOffHandler
{
    public function __construct(
        private readonly CreateWriteOffRepository $repository,
    ) {
    }

    public function handle(CreateWriteOffCommand $command): void
    {
        $this->repository->create($command->DTO);
    }
}
