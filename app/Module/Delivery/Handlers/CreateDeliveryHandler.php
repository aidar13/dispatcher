<?php

declare(strict_types=1);

namespace App\Module\Delivery\Handlers;

use App\Module\Delivery\Commands\CreateDeliveryCommand;
use App\Module\Delivery\Commands\CreateRouteSheetCommand;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Delivery\Contracts\Repositories\CreateDeliveryRepository;
use App\Module\Delivery\Models\Delivery;
use App\Module\Order\Contracts\Queries\InvoiceQuery;
use App\Module\Take\Commands\CreateCustomerCommand;
use App\Module\Take\Models\Customer;
use Carbon\Carbon;
use Illuminate\Bus\Dispatcher;

final class CreateDeliveryHandler
{
    public function __construct(
        private readonly Dispatcher $dispatcher,
        private readonly DeliveryQuery $deliveryQuery,
        private readonly CreateDeliveryRepository $deliveryRepository,
        private readonly InvoiceQuery $invoiceQuery,
    ) {
    }

    public function handle(CreateDeliveryCommand $command): void
    {
        if ($this->deliveryExists($command->DTO->internalId)) {
            return;
        }

        $invoice = $this->invoiceQuery->getById($command->DTO->invoiceId);

        /** @var Customer $customer */
        $customer = $this->dispatcher->dispatch(new CreateCustomerCommand($command->DTO->customerDTO, $invoice?->receiver?->sector_id, $invoice?->receiver?->dispatcher_sector_id));

        $delivery                         = new Delivery();
        $delivery->customer_id            = $customer->id;
        $delivery->internal_id            = $command->DTO->internalId;
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
        $delivery->created_at             = $command->DTO->createdAt ? Carbon::parse($command->DTO->createdAt) : Carbon::now();
        $delivery->status_id              = $delivery->getStatusFromIntegration($command->DTO->statusId);

        $this->deliveryRepository->create($delivery);

        dispatch(new CreateRouteSheetCommand($command->DTO->routeSheetId, $delivery->invoice_id));
    }

    private function deliveryExists(?int $internalId): bool
    {
        if (!$internalId) {
            return false;
        }

        return (bool)$this->deliveryQuery->getByInternalId($internalId);
    }
}
