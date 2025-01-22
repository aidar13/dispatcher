<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Handlers\Delivery;

use App\Models\User;
use App\Module\CourierApp\Commands\Delivery\ApproveDeliveryByInvoiceIdCommand;
use App\Module\CourierApp\Events\Delivery\DeliveryStatusChangedEvent;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Delivery\Contracts\Repositories\UpdateDeliveryRepository;
use App\Module\Delivery\Models\Delivery;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\StatusSource;
use App\Module\Status\Models\StatusType;
use Illuminate\Support\Facades\Log;

final readonly class ApproveDeliveryByInvoiceIdHandler
{
    public function __construct(
        private DeliveryQuery $query,
        private UpdateDeliveryRepository $repository,
    ) {
    }

    public function handle(ApproveDeliveryByInvoiceIdCommand $command): void
    {
        $deliveries = $this->query->getByInvoiceNumberAndVerify($command->DTO->invoiceNumber, $command->DTO->verifyType);

        /** @var Delivery $delivery */
        foreach ($deliveries as $delivery) {
            if (!in_array($delivery->status_id, [StatusType::ID_DELIVERY_CREATED, StatusType::ID_IN_DELIVERING])) {
                continue;
            }

            $delivery->delivered_at = $command->DTO->deliveredAt;
            $delivery->status_id    = StatusType::ID_DELIVERED;

            Log::info('Закрытие доставки в deliveries: ' . $delivery->id);
            $this->repository->update($delivery);
            Log::info('Доствка закрыта с id: ' . $delivery->id);

            event(new DeliveryStatusChangedEvent(
                $delivery->id,
                $delivery->courier->user_id ?? User::USER_ADMIN,
                RefStatus::CODE_DELIVERED,
                StatusSource::ID_COURIER_APP_V2
            ));
        }
    }
}
