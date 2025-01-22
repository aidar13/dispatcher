<?php

declare(strict_types=1);

namespace App\Module\Courier\Handlers\Integration;

use App\Module\Courier\Commands\Integration\CreateCourierLicensesCommand;
use App\Module\Courier\Commands\Integration\UpdateCourierLicensesCommand;
use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\Courier\Contracts\Repositories\UpdateCourierLicenseRepository;

final readonly class UpdateCourierLicensesHandler
{
    public function __construct(
        private CourierQuery $courierQuery,
        private UpdateCourierLicenseRepository $repository,
    ) {
    }

    public function handle(UpdateCourierLicensesCommand $command): void
    {
        $courier = $this->courierQuery->getById($command->courierId);

        if (!$courier->license) {
            dispatch(new CreateCourierLicensesCommand($courier->id, $command->DTO));
            return;
        }

        $courierLicense                            = $courier->license;
        $courierLicense->courier_id                = $courier->id;
        $courierLicense->identify_card_number      = $command->DTO->identifyCardNumber;
        $courierLicense->identify_card_issue_date  = $command->DTO->identifyCardIssueDate;
        $courierLicense->driver_license_number     = $command->DTO->driverLicenseNumber;
        $courierLicense->driver_license_issue_date = $command->DTO->driverLicenseIssueDate;

        $this->repository->update($courierLicense);
    }
}
