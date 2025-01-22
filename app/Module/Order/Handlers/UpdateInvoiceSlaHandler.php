<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\UpdateInvoiceSlaCommand;
use App\Module\Order\Contracts\Queries\InvoiceQuery;
use App\Module\Order\Contracts\Queries\SlaQuery;
use App\Module\Order\Contracts\Repositories\UpdateInvoiceRepository;
use Illuminate\Support\Facades\Log;

final class UpdateInvoiceSlaHandler
{
    public function __construct(
        private readonly SlaQuery $slaQuery,
        private readonly InvoiceQuery $invoiceQuery,
        private readonly UpdateInvoiceRepository $updateInvoiceRepository
    ) {
    }

    public function handle(UpdateInvoiceSlaCommand $command): void
    {
        $invoice = $this->invoiceQuery->getById($command->invoiceId);

        $sender   = $invoice?->order?->sender;
        $receiver = $invoice?->receiver;

        if (!$sender || !$receiver) {
            return;
        }

        $sla = $this->slaQuery->findSlaByCity(
            $sender->city_id,
            $receiver->city_id,
            $invoice->shipment_id
        );

        if (!$sla) {
            return;
        }

        $invoice->sla_date = $sla->getSla(
            $invoice?->created_at,
            (bool)$sender->warehouse_id,
            (bool)$receiver->warehouse_id
        );

        Log::info('Обновление sla накладной: ', [
            'invoiceNumber' => $invoice->invoice_number,
            'sla'           => $invoice->sla_date
        ]);

        $this->updateInvoiceRepository->update($invoice);
    }
}
