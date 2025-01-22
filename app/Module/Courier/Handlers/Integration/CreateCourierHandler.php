<?php

declare(strict_types=1);

namespace App\Module\Courier\Handlers\Integration;

use App\Module\Courier\Commands\Integration\CreateCourierCommand;
use App\Module\Courier\Commands\Integration\CreateCourierLicensesCommand;
use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\Courier\Contracts\Repositories\CreateCourierRepository;
use App\Module\Courier\DTO\Integration\CourierDTO;
use App\Module\Courier\Models\Courier;
use App\Module\Courier\Models\CourierScheduleType;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final readonly class CreateCourierHandler
{
    public function __construct(
        private CreateCourierRepository $createCourierRepository,
        private CourierQuery $courierQuery
    ) {
    }

    public function handle(CreateCourierCommand $command): void
    {
        if ($this->courierExists($command->DTO->id)) {
            return;
        }

        $courier                       = new Courier();
        $courier->id                   = $command->DTO->id;
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
        $courier->schedule_type_id     = CourierScheduleType::ID_THIRD_WAVE;
        $courier->created_at           = $command->DTO->createdAt;

        $this->createCourierRepository->create($courier);

        $this->createCourierLicenses($command->DTO->id, $command->DTO);
    }

    private function courierExists(int $id): bool
    {
        try {
            $this->courierQuery->getById($id);

            return true;
        } catch (ModelNotFoundException) {
            return false;
        }
    }

    private function createCourierLicenses(int $courierId, CourierDTO $courierDTO): void
    {
        if (!empty($courierDTO->courierLicense)) {
            dispatch(new CreateCourierLicensesCommand($courierId, $courierDTO->courierLicense));
        }
    }
}
