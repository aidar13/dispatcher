<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Handlers\CourierPayment;

use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\Courier\Models\Courier;
use App\Module\CourierApp\Commands\CourierPayment\SaveCourierPaymentFilesCommand;
use App\Module\CourierApp\Contracts\Repositories\CourierPayment\CreateCourierPaymentRepository;
use App\Module\CourierApp\Models\CourierPayment;
use App\Module\File\Commands\CreateFileCommand;

final class SaveCourierPaymentFilesHandler
{
    public function __construct(
        private readonly CourierQuery $query,
        private readonly CreateCourierPaymentRepository $repository,
    ) {
    }

    public function handle(SaveCourierPaymentFilesCommand $command): void
    {
        $courier = $this->query->getByUserId($command->userId);

        $model = new CourierPayment();
        $model->setCourierId($courier?->id);
        $model->setUserId($command->userId);
        $model->setClientId($command->DTO->clientId);
        $model->setClientType($command->DTO->clientType);
        $model->setType($command->DTO->type);
        $model->setCost($command->DTO->cost);

        $this->repository->create($model);

        $this->saveChecks($command, $model);
    }

    private function saveChecks(SaveCourierPaymentFilesCommand $command, CourierPayment $courierPayment): void
    {
        foreach ($command->DTO->checks as $file) {
            dispatch(new CreateFileCommand(
                $file,
                $courierPayment->getFileType(),
                Courier::CHECKS_BUCKET_NAME,
                $file->getClientOriginalName(),
                $command->DTO->clientId,
                $command->DTO->clientType,
                $command->userId,
            ));
        }
    }
}
