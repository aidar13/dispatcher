<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\CreateInvoiceCargoCommand;
use App\Module\Order\Contracts\Repositories\CreateInvoiceCargoRepository;
use App\Module\Order\Models\InvoiceCargo;

final class CreateInvoiceCargoHandler
{
    public function __construct(
        private readonly CreateInvoiceCargoRepository $invoiceCargoRepository,
    ) {
    }

    public function handle(CreateInvoiceCargoCommand $command): void
    {
        $invoiceCargo                = new InvoiceCargo();
        $invoiceCargo->invoice_id    = $command->DTO->invoiceId;
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
        $invoiceCargo->created_at    = $command->DTO->createdAt;

        $this->invoiceCargoRepository->create($invoiceCargo);
    }
}
