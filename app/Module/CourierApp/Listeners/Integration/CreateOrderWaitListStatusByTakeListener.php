<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Listeners\Integration;

use App\Module\CourierApp\Events\OrderTake\TakeWaitListStatusChangedEvent;
use App\Module\Status\Commands\SendOrderStatusToCabinetCommand;
use App\Module\Status\DTO\SendOrderStatusDTO;
use App\Module\Take\Contracts\Queries\OrderTakeQuery;

final class CreateOrderWaitListStatusByTakeListener
{
    public function __construct(
        private readonly OrderTakeQuery $query
    ) {
    }

    public function handle(TakeWaitListStatusChangedEvent $event): void
    {
        $take = $this->query->getById($event->takeId);

        $dto = new SendOrderStatusDTO();
        $dto->setInvoiceNumber($take->invoice->invoice_number);
        $dto->setCode($event->DTO->statusCode);
        $dto->setCreatedAt(now());
        $dto->setUserId($event->DTO->userId);

        dispatch(new SendOrderStatusToCabinetCommand($dto));
    }
}
