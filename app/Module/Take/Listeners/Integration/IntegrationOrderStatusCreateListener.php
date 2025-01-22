<?php

declare(strict_types=1);

namespace App\Module\Take\Listeners\Integration;

use App\Module\CourierApp\Events\Delivery\DeliveryStatusChangedEvent;
use App\Module\CourierApp\Events\OrderTake\OrderTakeStatusChangedEvent;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Status\Commands\SendOrderStatusToCabinetCommand;
use App\Module\Status\DTO\SendOrderStatusDTO;
use App\Module\Take\Contracts\Queries\OrderTakeQuery;
use App\Module\Take\Events\OrderTakeAssignedToCourierEvent;
use Illuminate\Bus\Dispatcher;

final readonly class IntegrationOrderStatusCreateListener
{
    public function __construct(
        private OrderTakeQuery $takeQuery,
        private DeliveryQuery $deliveryQuery,
        private Dispatcher $dispatcher
    ) {
    }

    public function handle(
        OrderTakeAssignedToCourierEvent|
        OrderTakeStatusChangedEvent|
        DeliveryStatusChangedEvent $event
    ): void {
        if ($event instanceof DeliveryStatusChangedEvent) {
            $model = $this->deliveryQuery->getById($event->id);
        }

        if (!($event instanceof DeliveryStatusChangedEvent)) {
            $model = $this->takeQuery->getById($event->orderTakeId);
        }

        $dto = new SendOrderStatusDTO();
        $dto->setInvoiceNumber($model->invoice->invoice_number);
        $dto->setCode($event->statusCode);
        $dto->setCreatedAt(now());
        $dto->setUserId($event->userId);
        $dto->setStatusSourceId($event->statusSourceId);

        $this->dispatcher->dispatch(new SendOrderStatusToCabinetCommand($dto));
    }
}
