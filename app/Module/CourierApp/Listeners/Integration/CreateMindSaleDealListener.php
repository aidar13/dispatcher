<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Listeners\Integration;

use App\Module\CourierApp\Events\Delivery\DeliveryInfoWaitListStatusChangedEvent;
use App\Module\CourierApp\Events\OrderTake\OrderTakeInfoWaitListStatusChangedInfoEvent;
use App\Module\CRM\Commands\CreateDeliveryClientsDealsCommand;
use App\Module\CRM\Commands\CreateTakeClientsDealsCommand;
use App\Module\Status\Models\RefStatus;

final class CreateMindSaleDealListener
{
    public function handle(DeliveryInfoWaitListStatusChangedEvent|OrderTakeInfoWaitListStatusChangedInfoEvent $event): void
    {
        if (!RefStatus::statusCodeInWaitingList($event->DTO->statusCode)) {
            return;
        }

        if ($event instanceof DeliveryInfoWaitListStatusChangedEvent) {
            dispatch(new CreateDeliveryClientsDealsCommand($event->id, $event->DTO->statusCode, $event->DTO->userId));
        }

        if ($event instanceof OrderTakeInfoWaitListStatusChangedInfoEvent) {
            dispatch(new CreateTakeClientsDealsCommand($event->orderId, $event->DTO->statusCode, $event->DTO->userId));
        }
    }
}
