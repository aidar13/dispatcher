<?php

declare(strict_types=1);

namespace App\Module\Order\DTO;

use Illuminate\Support\Carbon;

final class AdditionalServiceValueDTO
{
    public int $id;
    public int $typeId;
    public ?int $statusId;
    public int $clientId;
    public string $clientType;
    public ?float $value;
    public ?int $duration;
    public ?float $costPerHour;
    public ?float $costTotal;
    public ?float $paidPricePerHour;
    public ?float $paidPriceTotal;
    public ?int $carrierId;
    public Carbon $createdAt;
    public Carbon $updatedAt;

    public static function fromEvent($event): AdditionalServiceValueDTO
    {
        $self = new self();

        $self->id               = $event->id;
        $self->typeId           = $event->typeId;
        $self->statusId         = $event->statusId;
        $self->clientId         = $event->clientId;
        $self->clientType       = $event->clientType;
        $self->value            = $event->value;
        $self->duration         = $event->duration;
        $self->costPerHour      = $event->costPerHour;
        $self->costTotal        = $event->costTotal;
        $self->paidPricePerHour = $event->paidPricePerHour;
        $self->paidPriceTotal   = $event->paidPriceTotal;
        $self->carrierId        = $event->carrierId;
        $self->createdAt        = new Carbon($event->createdAt);
        $self->updatedAt        = new Carbon($event->updatedAt);

        return $self;
    }
}
