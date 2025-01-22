<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers\Integration;

use App\Module\Order\Commands\Integration\CancelInvoiceInCabinetCommand;
use App\Module\Order\Contracts\Repositories\Integration\CancelInvoiceRepository;

final class CancelInvoiceInCabinetHandler
{
    public function __construct(
        private readonly CancelInvoiceRepository $repository,
    ) {
    }

    public function handle(CancelInvoiceInCabinetCommand $command): void
    {
        $this->repository->cancel($command->DTO);
    }
}
