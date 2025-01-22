<?php

declare(strict_types=1);

namespace App\Module\Delivery\Handlers;

use App\Module\Delivery\Commands\SetStatusToDeliveryByInvoiceIdCommand;
use App\Module\Delivery\Commands\SetStatusToDeliveryCommand;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Delivery\Models\Delivery;

final class SetStatusToDeliveryByInvoiceIdHandler
{
    public function __construct(
        private readonly DeliveryQuery $query,
    ) {
    }

    public function handle(SetStatusToDeliveryByInvoiceIdCommand $command): void
    {
        $deliveries = $this->query->getAllByInvoiceId($command->invoiceId, ['id', 'invoice_id', 'status_id']);

        /** @var Delivery $delivery */
        foreach ($deliveries as $delivery) {
            if (
                $delivery->isDelivered() ||
                $delivery->isReturned()
            ) {
                continue;
            }

            dispatch(new SetStatusToDeliveryCommand($delivery->id, $command->statusId));
        }
    }
}
