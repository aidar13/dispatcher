<?php

declare(strict_types=1);

namespace App\Module\Status\Handlers;

use App\Module\Status\Commands\SendOrderStatusToCabinetCommand;
use App\Module\Take\Contracts\Repositories\Integration\IntegrationOrderStatusRepository;

final readonly class SendOrderStatusToCabinetHandler
{
    public function __construct(
        private IntegrationOrderStatusRepository $repository
    ) {
    }

    public function handle(SendOrderStatusToCabinetCommand $command): void
    {
        $this->repository->sendStatusToCabinet($command->DTO);
    }
}
