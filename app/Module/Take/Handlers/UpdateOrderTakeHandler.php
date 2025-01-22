<?php

declare(strict_types=1);

namespace App\Module\Take\Handlers;

use App\Module\Take\Commands\CreateOrderTakeCommand;
use App\Module\Take\Commands\UpdateCustomerCommand;
use App\Module\Take\Commands\UpdateOrderTakeCommand;
use App\Module\Take\Contracts\Queries\OrderTakeQuery;
use App\Module\Take\Contracts\Repositories\UpdateOrderTakeRepository;
use App\Module\Take\DTO\OrderTakeDTO;
use App\Module\Take\Models\OrderTake;

final readonly class UpdateOrderTakeHandler
{
    public function __construct(
        private OrderTakeQuery $orderTakeQuery,
        private UpdateOrderTakeRepository $orderTakeRepository,
    ) {
    }

    public function handle(UpdateOrderTakeCommand $command): void
    {
        $take = $this->getTake($command->DTO);

        if (!$take && !$command->DTO->deletedAt) {
            dispatch(new CreateOrderTakeCommand($command->DTO));
            return;
        }

        dispatch(new UpdateCustomerCommand($take->customer_id, $command->DTO->customerDTO));

        $take->invoice_id    = $command->DTO->invoiceId;
        $take->company_id    = $command->DTO->companyId;
        $take->city_id       = $command->DTO->cityId;
        $take->take_date     = $command->DTO->takeDate;
        $take->shipment_type = $command->DTO->shipmentType;
        $take->places        = $command->DTO->places;
        $take->weight        = $command->DTO->weight;
        $take->volume        = $command->DTO->volume;
        $take->deleted_at    = $command->DTO->deletedAt;
        //TODO: refactor deleting take

        $this->orderTakeRepository->update($take);
    }

    private function getTake(OrderTakeDTO $DTO): ?OrderTake
    {
        if (!$DTO->internalId) {
            return $this->orderTakeQuery->getByInvoiceId($DTO->invoiceId);
        }

        return $this->orderTakeQuery->getByInternalId($DTO->internalId);
    }
}
