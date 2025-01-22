<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\IntegrationChangeTakeDateByOrderCommand;
use App\Module\Order\Contracts\Repositories\Integration\ChangeInvoiceTakeDataRepository;

final readonly class IntegrationChangeTakeDateByOrderHandler
{
    public function __construct(
        private ChangeInvoiceTakeDataRepository $repository,
    ) {
    }

    public function handle(IntegrationChangeTakeDateByOrderCommand $command): void
    {
        $this->repository->changeTakeDateByOrderInCabinet($command->DTO);
    }
}
