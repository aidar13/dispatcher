<?php

declare(strict_types=1);

namespace App\Module\Courier\Handlers\Integration;

use App\Module\Courier\Commands\Integration\UpdateCourierCommand;
use App\Module\Courier\Commands\Integration\UpdateCourierLicensesCommand;
use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\Courier\Contracts\Repositories\UpdateCourierRepository;
use App\Module\Courier\DTO\Integration\CourierDTO;

final readonly class UpdateCourierHandler
{
    public function __construct(
        private CourierQuery $courierQuery,
        private UpdateCourierRepository $updateCourierRepository,
    ) {
    }

    public function handle(UpdateCourierCommand $command): void
    {
        $courier = $this->courierQuery->getById($command->DTO->id);

        $courier->status_id            = $command->DTO->statusId;
        $courier->user_id              = $command->DTO->userId;
        $courier->company_id           = $command->DTO->companyId;
        $courier->dispatcher_sector_id = $command->DTO->dispatcherSectorId;
        $courier->car_id               = $command->DTO->carId;
        $courier->full_name            = $command->DTO->fullName;
        $courier->phone_number         = str_replace('+', '', $command->DTO->phoneNumber);
        $courier->is_active            = $command->DTO->isActive;
        $courier->code_1c              = $command->DTO->code1C;
        $courier->iin                  = $command->DTO->iin;
        $courier->payment_rate_type    = $command->DTO->paymentRateType;
        $courier->payment_amount       = $command->DTO->paymentAmount;
        $courier->created_at           = $command->DTO->createdAt;

        $this->updateCourierRepository->update($courier);

        $this->updateCourierLicenses($courier->id, $command->DTO);
    }

    private function updateCourierLicenses(int $courierId, CourierDTO $courierDTO): void
    {
        if (!empty($courierDTO->courierLicense)) {
            dispatch(new UpdateCourierLicensesCommand($courierId, $courierDTO->courierLicense));
        }
    }
}
