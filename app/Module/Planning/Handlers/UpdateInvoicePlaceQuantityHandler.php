<?php

declare(strict_types=1);

namespace App\Module\Planning\Handlers;

use App\Module\Order\Contracts\Queries\InvoiceQuery;
use App\Module\Order\Contracts\Repositories\UpdateInvoiceRepository;
use App\Module\Planning\Commands\UpdateInvoicePlaceQuantityCommand;
use App\Module\Planning\DTO\ContainerInvoiceInfoDTO;

final class UpdateInvoicePlaceQuantityHandler
{
    public function __construct(
        private readonly InvoiceQuery $query,
        private readonly UpdateInvoiceRepository $repository,
    ) {
    }

    public function handle(UpdateInvoicePlaceQuantityCommand $command): void
    {
        /** @var ContainerInvoiceInfoDTO $invoice */
        foreach ($command->invoices as $invoice) {
            $model = $this->query->getByInvoiceNumber($invoice->invoiceNumber);

            $model->place_quantity = $invoice->placesQuantity;

            $this->repository->update($model);
        }
    }
}
