<?php

declare(strict_types=1);

namespace App\Module\Planning\Handlers;

use App\Module\Planning\Commands\UpdateContainerInvoiceStatusesCommand;
use App\Module\Planning\Contracts\Queries\ContainerInvoiceQuery;
use App\Module\Planning\Contracts\Repositories\UpdateContainerInvoiceRepository;
use App\Module\Planning\DTO\ContainerInvoiceInfoDTO;
use App\Module\Planning\Events\PartiallyAssembledInvoicesNotificationEvent;
use App\Module\Planning\Models\ContainerInvoice;
use App\Module\Planning\Models\ContainerInvoiceStatus;

final class UpdateContainerInvoiceStatusesHandler
{
    public function __construct(
        private readonly ContainerInvoiceQuery $query,
        private readonly UpdateContainerInvoiceRepository $repository,
    ) {
    }

    /**
     * @psalm-suppress InvalidScalarArgument
     * @param UpdateContainerInvoiceStatusesCommand $command
     * @return void
     */
    public function handle(UpdateContainerInvoiceStatusesCommand $command): void
    {
        $partiallyAssembledInvoices = collect();
        $containerInvoices          = $this->query->getByContainerIdAndInvoiceNumbers(
            $command->DTO->containerId,
            $command->DTO->invoices->pluck('invoiceNumber')->toArray()
        );

        /** @var ContainerInvoiceInfoDTO $invoice */
        foreach ($command->DTO->invoices as $invoice) {
            /** @var ContainerInvoice $containerInvoice */
            $containerInvoice = $containerInvoices->where('invoice.invoice_number', $invoice->invoiceNumber)->first();

            if (!$containerInvoice) {
                continue;
            }

            $containerInvoice->status_id = $invoice->invoiceStatusId;

            $this->repository->update($containerInvoice);

            if ($invoice->invoiceStatusId === ContainerInvoiceStatus::ID_PARTIALLY_ASSEMBLED) {
                $partiallyAssembledInvoices->push($invoice->invoiceNumber);
            }
        }

        if ($partiallyAssembledInvoices->isNotEmpty()) {
            event(new PartiallyAssembledInvoicesNotificationEvent(
                $partiallyAssembledInvoices,
                $command->DTO->containerId
            ));
        }
    }
}
