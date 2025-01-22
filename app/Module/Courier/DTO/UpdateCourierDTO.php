<?php

declare(strict_types=1);

namespace App\Module\Courier\DTO;

use App\Module\Courier\Requests\UpdateCourierRequest;
use App\Traits\ToArrayTrait;

final class UpdateCourierDTO
{
    use ToArrayTrait;

    public string $iin;
    public string $fullName;
    public string $phoneNumber;
    public int $paymentRateType;
    public ?float $paymentAmount;
    public int $dispatcherSectorId;
    public int $companyId;
    public int $scheduleTypeId;
    public int $carId;

    public static function fromRequest(UpdateCourierRequest $request): self
    {
        $self                     = new self();
        $self->iin                = $request->get('iin');
        $self->fullName           = $request->get('fullName');
        $self->phoneNumber        = $request->get('phoneNumber');
        $self->paymentRateType    = $request->get('paymentRateType');
        $self->paymentAmount      = (float)$request->get('paymentAmount');
        $self->dispatcherSectorId = (int)$request->get('dispatcherSectorId');
        $self->companyId          = (int)$request->get('companyId');
        $self->scheduleTypeId     = (int)$request->get('scheduleTypeId');
        $self->carId              = (int)$request->get('carId');

        return $self;
    }
}
