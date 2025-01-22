<?php

declare(strict_types=1);

namespace App\Module\Courier\Handlers\Integration;

use App\Module\Courier\Commands\Integration\CreateCourierLicensesCommand;
use App\Module\Courier\Contracts\Repositories\CreateCourierLicenseRepository;
use App\Module\Courier\Models\CourierLicense;

final readonly class CreateCourierLicensesHandler
{
    public function __construct(
        private CreateCourierLicenseRepository $repository,
    ) {
    }

    public function handle(CreateCourierLicensesCommand $command): void
    {
        $courierLicense                            = new CourierLicense();
        $courierLicense->id                        = $command->DTO->id;
        $courierLicense->courier_id                = $command->courierId;
        $courierLicense->identify_card_number      = $command->DTO->identifyCardNumber;
        $courierLicense->identify_card_issue_date  = $command->DTO->identifyCardIssueDate;
        $courierLicense->driver_license_number     = $command->DTO->driverLicenseNumber;
        $courierLicense->driver_license_issue_date = $command->DTO->driverLicenseIssueDate;

        $this->repository->create($courierLicense);
    }
}
