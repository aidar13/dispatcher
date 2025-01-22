<?php

declare(strict_types=1);

namespace App\Module\Planning\Handlers;

use App\Module\Planning\Commands\CreateContainerInvoicesCommand;
use App\Module\Planning\Contracts\Queries\ContainerInvoiceQuery;
use App\Module\Planning\Contracts\Repositories\CreateContainerInvoiceRepository;
use App\Module\Planning\Models\ContainerInvoice;

final readonly class CreateContainerInvoicesHandler
{
    public function __construct(
        private ContainerInvoiceQuery $containerInvoiceQuery,
        private CreateContainerInvoiceRepository $containerInvoiceRepository
    ) {
    }

    public function handle(CreateContainerInvoicesCommand $command): void
    {
        $position = $this->containerInvoiceQuery->getLastInvoicePosition($command->containerId);

        foreach ($command->invoiceIds as $invoiceId) {
            $position++;

            $containerInvoice = new ContainerInvoice();
            $containerInvoice->setContainerId($command->containerId);
            $containerInvoice->setInvoiceId((int)$invoiceId);
            $containerInvoice->setPosition($position);

            $this->containerInvoiceRepository->create($containerInvoice);
        }
    }
}
