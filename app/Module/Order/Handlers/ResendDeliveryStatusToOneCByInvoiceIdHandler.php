<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\CourierApp\Commands\Delivery\IntegrationOneC\ChangeDeliveryStatusInOneCCommand;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Delivery\Models\Delivery;
use App\Module\Order\Commands\ResendDeliveryStatusToOneCByInvoiceIdCommand;

final readonly class ResendDeliveryStatusToOneCByInvoiceIdHandler
{
    public function __construct(
        private DeliveryQuery $deliveryQuery,
    ) {
    }

    public function handle(ResendDeliveryStatusToOneCByInvoiceIdCommand $command): void
    {
        $deliveries = $this->deliveryQuery->getAllByInvoiceId($command->invoiceId, ['id', 'status_id']);

        /** @var Delivery $delivery */
        foreach ($deliveries as $delivery) {
            if (!$delivery->isDelivered()) {
                continue;
            }

            dispatch_sync(new ChangeDeliveryStatusInOneCCommand(
                $delivery->id
            ));
            return;
        }
    }
}
