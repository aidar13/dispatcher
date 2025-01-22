<?php

declare(strict_types=1);

namespace App\Module\Order\DTO;

use Illuminate\Support\Carbon;

final class SenderDTO
{
    public int $id;
    public ?int $cityId;
    public ?string $fullAddress;
    public ?string $title;
    public ?string $fullName;
    public ?string $phone;
    public ?string $additionalPhone;
    public ?string $latitude;
    public ?string $longitude;

    public ?string $street;
    public ?string $house;
    public ?string $office;
    public ?string $index;
    public ?string $comment;
    public ?int $warehouseId;
    public ?Carbon $createdAt;

    public static function fromEvent($event): self
    {
        $self                  = new self();
        $self->id              = $event->id;
        $self->cityId          = $event->cityId;
        $self->fullAddress     = $event->fullAddress;
        $self->title           = $event->title;
        $self->fullName        = $event->fullName;
        $self->phone           = $event->phone;
        $self->additionalPhone = $event->additionalPhone;
        $self->latitude        = $event->latitude;
        $self->longitude       = $event->longitude;
        $self->street          = $event->street;
        $self->house           = $event->house;
        $self->office          = $event->office;
        $self->index           = $event->index;
        $self->comment         = $event->comment;
        $self->warehouseId     = $event->warehouseId;
        $self->createdAt       = new Carbon($event->createdAt);

        return $self;
    }
}
