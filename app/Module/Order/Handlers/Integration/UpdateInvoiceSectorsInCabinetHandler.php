<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers\Integration;

use App\Module\Order\Commands\Integration\UpdateInvoiceSectorsInCabinetCommand;
use App\Module\Order\Contracts\Queries\InvoiceQuery;
use App\Module\Order\Contracts\Repositories\Integration\UpdateInvoiceSectorsRepository;

final readonly class UpdateInvoiceSectorsInCabinetHandler
{
    public function __construct(
        private InvoiceQuery $query,
        private UpdateInvoiceSectorsRepository $repository,
    ) {
    }

    public function handle(UpdateInvoiceSectorsInCabinetCommand $command): void
    {
        $invoice = $this->query->getById($command->id);

        $this->repository->update($invoice);
    }
}
