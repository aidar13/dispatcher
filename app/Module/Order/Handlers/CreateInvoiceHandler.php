<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\CreateInvoiceCargoCommand;
use App\Module\Order\Commands\CreateInvoiceCommand;
use App\Module\Order\Contracts\Queries\InvoiceQuery;
use App\Module\Order\Contracts\Repositories\CreateInvoiceRepository;
use App\Module\Order\Events\InvoiceCreatedEvent;
use App\Module\Order\Models\Invoice;

final class CreateInvoiceHandler
{
    public function __construct(
        private readonly InvoiceQuery $invoiceQuery,
        private readonly CreateInvoiceRepository $createInvoiceRepository
    ) {
    }

    public function handle(CreateInvoiceCommand $command): void
    {
        if ($this->hasInvoice($command->DTO->id)) {
            return;
        }

        $invoice                         = new Invoice();
        $invoice->id                     = $command->DTO->id;
        $invoice->invoice_number         = $command->DTO->invoiceNumber;
        $invoice->order_id               = $command->DTO->orderId;
        $invoice->status_id              = $command->DTO->statusId;
        $invoice->receiver_id            = $command->DTO->receiverId;
        $invoice->direction_id           = $command->DTO->directionId;
        $invoice->shipment_id            = $command->DTO->shipmentId;
        $invoice->period_id              = $command->DTO->periodId;
        $invoice->take_date              = $command->DTO->takeDate;
        $invoice->take_time              = $command->DTO->takeTime;
        $invoice->payment_type           = $command->DTO->paymentType;
        $invoice->payment_method         = $command->DTO->paymentMethod;
        $invoice->code_1c                = $command->DTO->code1c;
        $invoice->dop_invoice_number     = $command->DTO->dopInvoiceNumber;
        $invoice->cash_sum               = $command->DTO->cashSum;
        $invoice->should_return_document = $command->DTO->shouldReturnDocument;
        $invoice->weekend_delivery       = $command->DTO->weekendDelivery;
        $invoice->verify                 = $command->DTO->verify;
        $invoice->type                   = $command->DTO->type;
        $invoice->created_at             = $command->DTO->createdAt;
        $invoice->cargo_type             = $command->DTO->cargoType;
        $invoice->payer_company_id       = $command->DTO->payerCompanyId;

        $this->createInvoiceRepository->create($invoice);

        dispatch(new CreateInvoiceCargoCommand($command->DTO->invoiceCargo));

        event(new InvoiceCreatedEvent($invoice->id));
    }

    private function hasInvoice(?int $id): bool
    {
        return (bool)$this->invoiceQuery->getById($id);
    }
}
