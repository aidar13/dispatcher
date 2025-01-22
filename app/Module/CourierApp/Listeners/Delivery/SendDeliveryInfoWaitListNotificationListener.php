<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Listeners\Delivery;

use App\Module\CourierApp\Events\Delivery\DeliveryInfoWaitListStatusChangedEvent;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Delivery\Models\Delivery;
use App\Module\DispatcherSector\Contracts\Queries\DispatchersSectorUserQuery;
use App\Module\Notification\DTO\WebNotificationDTO;
use App\Module\Notification\Events\Integration\SendWebNotificationEvent;
use App\Module\Notification\Models\NotificationType;

final class SendDeliveryInfoWaitListNotificationListener
{
    public function __construct(
        private readonly DeliveryQuery $deliveryQuery,
        private readonly DispatchersSectorUserQuery $dispatchersSectorQuery,
    ) {
    }

    public function handle(DeliveryInfoWaitListStatusChangedEvent $event): void
    {
        $delivery  = $this->deliveryQuery->getById($event->id);

        event(new SendWebNotificationEvent($this->getDTO($delivery, $event->DTO->statusCode)));
    }

    private function getDTO(Delivery $delivery, int $statusCode): WebNotificationDTO
    {
        $dsUserIds = $this->dispatchersSectorQuery->getAllDispatcherSectorUserIdsByCityId($delivery->city_id);

        $dto = new WebNotificationDTO();
        $dto->setObjectId($delivery->invoice->id);
        $dto->setTypeId(NotificationType::ID_DELIVERY_WAIT_LIST_STATUS_CREATED);
        $dto->setUserIds($dsUserIds);
        $dto->setData([
            'statusCode'    => $statusCode,
            'statusName'    => $delivery->waitListStatus?->name,
            'invoiceNumber' => $delivery->invoice->invoice_number
        ]);

        return $dto;
    }
}
