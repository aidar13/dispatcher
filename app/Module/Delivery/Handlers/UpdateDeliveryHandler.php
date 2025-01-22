<?php

declare(strict_types=1);

namespace App\Module\Delivery\Handlers;

use App\Module\Delivery\Commands\UpdateDeliveryCommand;
use App\Module\Delivery\Commands\UpdateRouteSheetCommand;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Delivery\Contracts\Repositories\UpdateDeliveryRepository;
use App\Module\Take\Commands\UpdateCustomerCommand;

final class UpdateDeliveryHandler
{
    public function __construct(
        private readonly DeliveryQuery $deliveryQuery,
        private readonly UpdateDeliveryRepository $deliveryRepository,
    ) {
    }

    public function handle(UpdateDeliveryCommand $command): void
    {
        $delivery = $this->deliveryQuery->getByInternalId($command->DTO->internalId);

        if (!$delivery) {
            return;
        }

        dispatch(new UpdateCustomerCommand($delivery->customer_id, $command->DTO->customerDTO));

        $delivery->invoice_id             = $command->DTO->invoiceId;
        $delivery->invoice_number         = $command->DTO->invoiceNumber;
        $delivery->company_id             = $command->DTO->companyId;
        $delivery->city_id                = $command->DTO->cityId;
        $delivery->courier_id             = $command->DTO->courierId;
        $delivery->wait_list_status_id    = $command->DTO->waitListStatusId;
        $delivery->places                 = $command->DTO->places;
        $delivery->weight                 = $command->DTO->weight;
        $delivery->volume                 = $command->DTO->volume;
        $delivery->volume_weight          = $command->DTO->volumeWeight;
        $delivery->delivery_receiver_name = $command->DTO->deliveryReceiverName;
        $delivery->courier_comment        = $command->DTO->courierComment;
        $delivery->delivered_at           = $command->DTO->deliveredAt;
        $delivery->status_id              = $delivery->getStatusFromIntegration($command->DTO->statusId);

        $this->deliveryRepository->update($delivery);

        dispatch(new UpdateRouteSheetCommand($delivery->id));
    }
}
