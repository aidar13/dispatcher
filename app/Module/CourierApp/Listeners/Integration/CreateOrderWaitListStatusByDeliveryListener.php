<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Listeners\Integration;

use App\Module\CourierApp\Events\Delivery\DeliveryInfoWaitListStatusChangedEvent;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Status\Commands\SendOrderStatusToCabinetCommand;
use App\Module\Status\DTO\SendOrderStatusDTO;

final class CreateOrderWaitListStatusByDeliveryListener
{
    public function __construct(
        private readonly DeliveryQuery $deliveryQuery
    ) {
    }

    public function handle(DeliveryInfoWaitListStatusChangedEvent $event): void
    {
        $delivery = $this->deliveryQuery->getById($event->id);

        $dto = new SendOrderStatusDTO();
        $dto->setInvoiceNumber($delivery->invoice->invoice_number);
        $dto->setCode($event->DTO->statusCode);
        $dto->setCreatedAt(now());
        $dto->setUserId($event->DTO->userId);

        dispatch(new SendOrderStatusToCabinetCommand($dto));
    }
}
