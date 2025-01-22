<?php

declare(strict_types=1);

namespace App\Module\Courier\DTO\Integration;

class CourierLicenseDTO
{
    public ?int $id;
    public ?string $identifyCardNumber;
    public ?string $identifyCardIssueDate;
    public ?string $driverLicenseNumber;
    public ?string $driverLicenseIssueDate;

    public static function fromDTO($courierLicense): self
    {
        $self                         = new self();
        $self->id                     = $courierLicense->id ?? null;
        $self->identifyCardNumber     = $courierLicense->identifyCardNumber ?? null;
        $self->identifyCardIssueDate  = $courierLicense->identifyCardIssueDate ?? null;
        $self->driverLicenseNumber    = $courierLicense->driverLicenseNumber ?? null;
        $self->driverLicenseIssueDate = $courierLicense->driverLicenseIssueDate ?? null;

        return $self;
    }
}
