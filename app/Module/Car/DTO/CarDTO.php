<?php

declare(strict_types=1);

namespace App\Module\Car\DTO;

use Illuminate\Support\Carbon;

final class CarDTO
{
    public int $id;
    public int $statusId;
    public ?int $companyId;
    public ?int $vehicleTypeId;
    public ?string $code1C;
    public ?string $number;
    public ?string $model;
    public ?int $cubature;
    public ?Carbon $createdAt;

    public static function fromEvent($event): CarDTO
    {
        $self = new self();

        $self->id            = $event->id;
        $self->statusId      = $event->statusId;
        $self->companyId     = $event->companyId;
        $self->vehicleTypeId = $event->vehicleTypeId;
        $self->code1C        = $event->code1C;
        $self->number        = $event->number;
        $self->model         = $event->model;
        $self->cubature      = $event->cubature;
        $self->createdAt     = new Carbon($event->createdAt);

        return $self;
    }
}
