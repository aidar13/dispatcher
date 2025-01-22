<?php

declare(strict_types=1);

namespace App\Module\Courier\DTO;

use App\Module\Courier\Requests\CourierExportRequest;
use App\Traits\ToArrayTrait;

final class CourierExportDTO
{
    use ToArrayTrait;

    public ?string $fromDate;
    public ?string $toDate;
    public ?array $dispatcherSectorIds;
    public ?array $statusIds;
    public ?string $createdAtFrom;
    public ?string $createdAtUntil;
    public ?string $name;
    public ?string $iin;
    public ?string $phoneNumber;
    public ?int $companyId;
    public ?string $carNumber;
    public ?string $carModel;
    public ?int $shiftId;
    public ?int $id;
    public ?string $code1C;

    public static function fromRequest(CourierExportRequest $request): self
    {
        $self                      = new self();
        $self->fromDate            = $request->get('fromDate');
        $self->toDate              = $request->get('toDate');
        $self->statusIds           = $request->get('statusIds');
        $self->dispatcherSectorIds = $request->get('dispatcherSectorIds');
        $self->createdAtFrom       = $request->get('createdAtFrom');
        $self->createdAtUntil      = $request->get('createdAtUntil');
        $self->name                = $request->get('name');
        $self->iin                 = $request->get('iin');
        $self->phoneNumber         = $request->get('phoneNumber');
        $self->companyId           = (int)$request->get('companyId') ?: null;
        $self->carNumber           = $request->get('carNumber');
        $self->carModel            = $request->get('carModel');
        $self->shiftId             = (int)$request->get('shiftId') ?: null;
        $self->id                  = (int)$request->get('id') ?: null;
        $self->code1C              = $request->get('code1C');

        return $self;
    }
}
