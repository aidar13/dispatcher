<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Handlers\Delivery;

use App\Module\CourierApp\Commands\Delivery\SetDeliveryInfoWaitListStatusCommand;
use App\Module\CourierApp\Events\Delivery\DeliveryInfoWaitListStatusChangedEvent;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Delivery\Contracts\Repositories\UpdateDeliveryRepository;
use App\Module\Status\Contracts\Queries\OrderStatusQuery;
use App\Module\Status\Contracts\Queries\RefStatusQuery;
use DomainException;

final class SetDeliveryInfoWaitListStatusHandler
{
    public function __construct(
        private readonly DeliveryQuery $deliveryQuery,
        private readonly RefStatusQuery $refStatusQuery,
        private readonly OrderStatusQuery $orderStatusQuery,
        private readonly UpdateDeliveryRepository $repository,
    ) {
    }

    public function handle(SetDeliveryInfoWaitListStatusCommand $command): void
    {
        $delivery = $this->deliveryQuery->getByInvoiceId($command->id);

        if (!$delivery) {
            return;
        }

        if ($this->checkStatusCodeExistInPeriod($delivery->id, $command->DTO->statusCode)) {
            return;
        }

        $status = $this->refStatusQuery->findByCode($command->DTO->statusCode);

        if (!$status->wait_list_type) {
            throw new DomainException('Статус не найден!');
        }

        $delivery->wait_list_status_id = $status->id;

        $this->repository->update($delivery);

        event(new DeliveryInfoWaitListStatusChangedEvent($delivery->id, $command->DTO));
    }

    private function checkStatusCodeExistInPeriod(int $invoiceId, int $code): bool
    {
        $lastStatus = $this->orderStatusQuery->getLastByInvoiceId($invoiceId);

        return $lastStatus && $lastStatus->canCreateWaitListStatus($code);
    }
}
