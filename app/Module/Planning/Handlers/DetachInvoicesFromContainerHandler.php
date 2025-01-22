<?php

declare(strict_types=1);

namespace App\Module\Planning\Handlers;

use App\Module\Planning\Commands\DetachInvoicesFromContainerCommand;
use App\Module\Planning\Contracts\Queries\ContainerInvoiceQuery;
use App\Module\Planning\Contracts\Repositories\DeleteContainerInvoiceRepository;

final class DetachInvoicesFromContainerHandler
{
    public function __construct(
        private readonly ContainerInvoiceQuery $query,
        private readonly DeleteContainerInvoiceRepository $repository
    ) {
    }

    public function handle(DetachInvoicesFromContainerCommand $command): void
    {
        $containerInvoices = $this->query->getByInvoiceIds($command->invoiceIds);

        foreach ($containerInvoices as $containerInvoice) {
            $this->repository->delete($containerInvoice);
        }
    }
}
