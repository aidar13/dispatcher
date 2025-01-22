<?php

declare(strict_types=1);

namespace App\Module\Planning\Handlers;

use App\Module\Planning\Commands\DeleteContainerInvoiceCommand;
use App\Module\Planning\Contracts\Queries\ContainerInvoiceQuery;
use App\Module\Planning\Contracts\Repositories\DeleteContainerInvoiceRepository;

final class DeleteContainerInvoiceHandler
{
    public function __construct(
        private readonly ContainerInvoiceQuery $query,
        private readonly DeleteContainerInvoiceRepository $repository
    ) {
    }

    public function handle(DeleteContainerInvoiceCommand $command): void
    {
        $containerInvoice = $this->query->findByContainerIdAndInvoiceId($command->containerId, $command->invoiceId);

        $this->repository->delete($containerInvoice);
    }
}
