<?php

declare(strict_types=1);

namespace App\Module\Take\Handlers;

use App\Module\Take\Commands\SetWaitListStatusCommand;
use App\Module\Take\Contracts\Repositories\Integration\SetWaitListStatusRepositoryIntegration;

final class SetWaitListStatusHandler
{
    public function __construct(
        private readonly SetWaitListStatusRepositoryIntegration $repository
    ) {
    }

    public function handle(SetWaitListStatusCommand $command): void
    {
        $this->repository->setTakeWaitListStatusInCabinet(
            $command->orderId,
            $command->code,
            $command->userId
        );
    }
}
