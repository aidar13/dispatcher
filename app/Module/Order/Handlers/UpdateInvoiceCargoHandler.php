<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\UpdateInvoiceCargoCommand;
use App\Module\Order\Contracts\Queries\InvoiceCargoQuery;
use App\Module\Order\Contracts\Repositories\UpdateInvoiceCargoRepository;

final class UpdateInvoiceCargoHandler
{
    public function __construct(
        private readonly InvoiceCargoQuery $invoiceCargoQuery,
        private readonly UpdateInvoiceCargoRepository $invoiceCargoRepository,
    ) {
    }

    public function handle(UpdateInvoiceCargoCommand $command): void
    {
        $invoiceCargo = $this->invoiceCargoQuery->getByInvoiceId($command->DTO->invoiceId);

        if (!$invoiceCargo) {
            return;
        }

        $invoiceCargo->cargo_name    = $command->DTO->cargoName;
        $invoiceCargo->product_name  = $command->DTO->productName;
        $invoiceCargo->places        = $command->DTO->places;
        $invoiceCargo->weight        = $command->DTO->weight;
        $invoiceCargo->volume        = $command->DTO->volume;
        $invoiceCargo->volume_weight = $command->DTO->volumeWeight;
        $invoiceCargo->width         = $command->DTO->width;
        $invoiceCargo->height        = $command->DTO->height;
        $invoiceCargo->depth         = $command->DTO->depth;
        $invoiceCargo->cod_payment   = $command->DTO->codPayment;
        $invoiceCargo->annotation    = $command->DTO->annotation;
        $invoiceCargo->pack_code     = $command->DTO->cargoPackCode;
        $invoiceCargo->size_type     = $command->DTO->cargoSizeType;

        $this->invoiceCargoRepository->update($invoiceCargo);
    }
}
