<?php

declare(strict_types=1);

namespace App\Module\Courier\Handlers;

use App\Module\Courier\Commands\UpdateCourierCommand;
use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\Courier\Contracts\Repositories\UpdateCourierRepository;

final class UpdateCourierHandler
{
    public function __construct(
        private readonly CourierQuery $courierQuery,
        private readonly UpdateCourierRepository $updateCourierRepository
    ) {
    }

    public function handle(UpdateCourierCommand $command): void
    {
        $courier = $this->courierQuery->getById($command->id);

        $courier->iin                  = $command->DTO->iin;
        $courier->full_name            = $command->DTO->fullName;
        $courier->phone_number         = str_replace('+', '', $command->DTO->phoneNumber);
        $courier->payment_rate_type    = $command->DTO->paymentRateType;
        $courier->payment_amount       = $command->DTO->paymentAmount;
        $courier->dispatcher_sector_id = $command->DTO->dispatcherSectorId;
        $courier->company_id           = $command->DTO->companyId;
        $courier->schedule_type_id     = $command->DTO->scheduleTypeId;
        $courier->car_id               = $command->DTO->carId;

        $this->updateCourierRepository->update($courier);
    }
}
