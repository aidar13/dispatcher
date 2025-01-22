<?php

declare(strict_types=1);

namespace App\Module\Courier\Handlers\Integration;

use App\Module\Courier\Commands\Integration\CreateCourierPaymentCommand;
use App\Module\Courier\Contracts\Repositories\CreateCourierPaymentRepository;
use App\Module\CourierApp\Models\CourierPayment;

final class CreateCourierPaymentHandler
{
    public function __construct(
        private readonly CreateCourierPaymentRepository $repository,
    ) {
    }

    public function handle(CreateCourierPaymentCommand $command): void
    {
        $clientType = CourierPayment::getClientTypeByClientType($command->DTO->clientType);

        $model = new CourierPayment();
        $model->setId($command->DTO->id);
        $model->setCourierId($command->DTO->courierId);
        $model->setClientId($command->DTO->clientId);
        $model->setClientType($clientType);
        $model->setType($command->DTO->type);
        $model->setCost($command->DTO->cost);

        $this->repository->create($model);
    }
}
