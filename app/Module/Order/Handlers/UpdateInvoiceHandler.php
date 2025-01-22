<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\CreateInvoiceCommand;
use App\Module\Order\Commands\UpdateInvoiceCargoCommand;
use App\Module\Order\Commands\UpdateInvoiceCommand;
use App\Module\Order\Contracts\Queries\InvoiceQuery;
use App\Module\Order\Contracts\Repositories\UpdateInvoiceRepository;
use App\Module\Order\Events\InvoiceUpdatedEvent;
use Illuminate\Bus\Dispatcher;

final class UpdateInvoiceHandler
{
    public function __construct(
        private readonly Dispatcher $dispatcher,
        private readonly InvoiceQuery $invoiceQuery,
        private readonly UpdateInvoiceRepository $updateInvoiceRepository
    ) {
    }

    public function handle(UpdateInvoiceCommand $command): void
    {
        $invoice = $this->invoiceQuery->getById($command->DTO->id);

        if (!$invoice) {
            dispatch(new CreateInvoiceCommand($command->DTO));
            return;
        }

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
        $invoice->payer_company_id       = $command->DTO->payerCompanyId;
        $invoice->cargo_type             = $command->DTO->cargoType;

        $this->updateInvoiceRepository->update($invoice);

        $this->dispatcher->dispatch(new UpdateInvoiceCargoCommand($command->DTO->invoiceCargo));

        event(new InvoiceUpdatedEvent($invoice->id));
    }
}
