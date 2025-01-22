<?php

declare(strict_types=1);

namespace App\Module\Delivery\Handlers;

use App\Module\Delivery\Commands\DeleteReturnDeliveryCommand;
use App\Module\Delivery\Contracts\Queries\ReturnDeliveryQuery;
use App\Module\Delivery\Contracts\Repositories\DeleteReturnDeliveryRepository;

final class DeleteReturnDeliveryHandler
{
    public function __construct(
        private readonly ReturnDeliveryQuery $query,
        private readonly DeleteReturnDeliveryRepository $repository
    ) {
    }

    public function handle(DeleteReturnDeliveryCommand $command): void
    {
        $returns = $this->query->getByInvoiceId($command->invoiceId);

        foreach ($returns as $model) {
            $this->repository->delete($model);
        }
    }
}
