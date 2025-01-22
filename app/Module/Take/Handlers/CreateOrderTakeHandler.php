<?php

declare(strict_types=1);

namespace App\Module\Take\Handlers;

use App\Module\Order\Contracts\Queries\OrderQuery;
use App\Module\Status\Contracts\Services\TakeStatusService;
use App\Module\Take\Commands\CreateCustomerCommand;
use App\Module\Take\Commands\CreateOrderTakeCommand;
use App\Module\Take\Contracts\Queries\OrderTakeQuery;
use App\Module\Take\Contracts\Repositories\CreateOrderTakeRepository;
use App\Module\Take\DTO\OrderTakeDTO;
use App\Module\Take\Models\Customer;
use App\Module\Take\Models\OrderTake;
use Carbon\Carbon;
use Illuminate\Bus\Dispatcher;

final readonly class CreateOrderTakeHandler
{
    public function __construct(
        private Dispatcher $dispatcher,
        private OrderTakeQuery $orderTakeQuery,
        private CreateOrderTakeRepository $orderTakeRepository,
        private OrderQuery $orderQuery,
    ) {
    }

    public function handle(CreateOrderTakeCommand $command): void
    {
        if ($this->orderTakeExists($command->DTO)) {
            return;
        }

        $order = $this->orderQuery->getById($command->DTO->orderId);

        /** @var Customer $customer */
        $customer = $this->dispatcher->dispatch(new CreateCustomerCommand($command->DTO->customerDTO, $order?->sender?->sector_id, $order?->sender?->dispatcher_sector_id));

        $take                      = new OrderTake();
        $take->customer_id         = $customer->id;
        $take->internal_id         = $command->DTO->internalId;
        $take->order_id            = $command->DTO->orderId;
        $take->order_number        = $command->DTO->orderNumber;
        $take->invoice_id          = $command->DTO->invoiceId;
        $take->company_id          = $command->DTO->companyId;
        $take->city_id             = $command->DTO->cityId;
        $take->take_date           = $command->DTO->takeDate;
        $take->shipment_type       = $command->DTO->shipmentType;
        $take->places              = $command->DTO->places;
        $take->weight              = $command->DTO->weight;
        $take->volume              = $command->DTO->volume;
        $take->created_at          = Carbon::now();
        $take->status_id           = $this->getStatus($take);
        $take->courier_id          = null;
        $take->wait_list_status_id = null;

        $this->orderTakeRepository->create($take);
    }

    private function orderTakeExists(OrderTakeDTO $DTO): bool
    {
        if (!$DTO->internalId) {
            return (bool)$this->orderTakeQuery->getByInvoiceId($DTO->invoiceId);
        }

        return (bool)$this->orderTakeQuery->getByInternalId($DTO->internalId);
    }

    private function getStatus(OrderTake $take): int
    {
        /** @var TakeStatusService $service */
        $service = app(TakeStatusService::class);
        return $service->getTakeStatusByInvoiceIdAndDate($take->invoice_id, $take->created_at);
    }
}
