<?php

declare(strict_types=1);

namespace App\Module\Take\Handlers;

use App\Module\Order\Contracts\Queries\InvoiceQuery;
use App\Module\Take\Commands\AssignCourierToOrderIn1CCommand;
use App\Module\Take\Contracts\Repositories\Integration\AssignCourierToOrderIn1CRepository;
use Illuminate\Support\Facades\Redis;

final class AssignCourierToOrderIn1CHandler
{
    public function __construct(
        private readonly InvoiceQuery $query,
        private readonly AssignCourierToOrderIn1CRepository $courierToOrderIn1CRepository
    ) {
    }

    public function handle(AssignCourierToOrderIn1CCommand $command): void
    {
        Redis::throttle('assign_courier_1C')->block(2)->allow(1)->every(2)->then(function () use ($command) {
            $invoice = $this->query->getById($command->invoiceId);
            $this->courierToOrderIn1CRepository->assignCourierToOrder($invoice, $command->courierId, $command->orderNumber);
        }, function () use ($command) {
            $command->release(5);
        });
    }
}
