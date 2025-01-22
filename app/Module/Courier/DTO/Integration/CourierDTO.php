<?php

declare(strict_types=1);

namespace App\Module\Courier\DTO\Integration;

use Illuminate\Support\Carbon;

final class CourierDTO
{
    public int $id;
    public ?int $statusId;
    public ?string $iin;
    public ?int $userId;
    public int $companyId;
    public int $dispatcherSectorId;
    public string $fullName;
    public string $phoneNumber;
    public bool $isActive;
    public ?string $code1C;
    public ?int $paymentRateType;
    public ?float $paymentAmount;
    public ?int $carId;
    public Carbon $createdAt;
    public ?CourierLicenseDTO $courierLicense = null;

    public static function fromEvent($event): self
    {
        $self                     = new self();
        $self->id                 = (int)$event->id;
        $self->statusId           = (int)$event->statusId;
        $self->userId             = (int)$event->userId;
        $self->companyId          = (int)$event->companyId;
        $self->dispatcherSectorId = (int)$event->dispatcherSectorId;
        $self->fullName           = $event->fullName;
        $self->phoneNumber        = $event->phoneNumber;
        $self->isActive           = (bool)$event->isActive;
        $self->code1C             = $event->code1C;
        $self->paymentRateType    = $event->paymentRate;
        $self->paymentAmount      = $event->paymentAmount;
        $self->iin                = $event->iin;
        $self->createdAt          = new Carbon($event->createdAt);
        $self->carId              = (int)$event?->car?->id;
        $self->courierLicense     = $event->courierLicense ? CourierLicenseDTO::fromDTO($event->courierLicense) : null;

        return $self;
    }
}
