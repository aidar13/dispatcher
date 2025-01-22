<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Handlers\OrderTake;

use App\Module\CourierApp\Commands\OrderTake\ApproveOrderTakeCommand;
use App\Module\CourierApp\Commands\OrderTake\MassApproveTakesByInvoiceNumbersCommand;
use App\Module\Take\Contracts\Queries\OrderTakeQuery;
use App\Module\Take\Models\OrderTake;

final class MassApproveTakesByInvoiceNumbersHandler
{
    public function __construct(
        private readonly OrderTakeQuery $query,
    ) {
    }

    public function handle(MassApproveTakesByInvoiceNumbersCommand $command): void
    {
        $invoicesCollection = collect($command->DTO->invoices)->pluck('places', 'invoiceNumber');
        $invoiceNumbers     = $invoicesCollection->keys()->toArray();
        $orderTakes         = $this->query->getByInvoiceNumbers(
            $invoiceNumbers,
            ['id', 'invoice_id'],
            ['invoice:id,invoice_number'],
        );

        /** @var OrderTake $orderTake */
        foreach ($orderTakes as $orderTake) {
            $places = $invoicesCollection->get($orderTake->invoice->invoice_number);

            dispatch(new ApproveOrderTakeCommand($orderTake->id, (int)$places, $command->userId));
        }
    }
}
