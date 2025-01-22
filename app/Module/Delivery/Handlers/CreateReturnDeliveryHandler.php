<?php

declare(strict_types=1);

namespace App\Module\Delivery\Handlers;

use App\Module\Delivery\Commands\CreateReturnDeliveryCommand;
use App\Module\Delivery\Contracts\Repositories\CreateReturnDeliveryRepository;
use App\Module\Delivery\Models\ReturnDelivery;
use App\Module\Status\Contracts\Queries\OrderStatusQuery;

final class CreateReturnDeliveryHandler
{
    public function __construct(
        private readonly OrderStatusQuery $query,
        private readonly CreateReturnDeliveryRepository $repository
    ) {
    }

    public function handle(CreateReturnDeliveryCommand $command): void
    {
        $status = $this->query->getById($command->statusId);

        $model             = new ReturnDelivery();
        $model->invoice_id = $status->invoice_id;
        $model->created_at = $status->created_at;
        $model->user_id    = $status->user_id;

        $this->repository->create($model);
    }
}
