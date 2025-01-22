<?php

declare(strict_types=1);

namespace App\Module\Order\DTO;

final class UpdateSlaDTO
{
    public int $id;
    public int $cityFrom;
    public int $cityTo;
    public int $pickup;
    public int $processing;
    public int $transit;
    public int $delivery;
    public ?int $shipmentTypeId;

    public static function fromEvent($event): self
    {
        $self                 = new self();
        $self->id             = $event->DTO->id;
        $self->cityFrom       = $event->DTO->cityFrom;
        $self->cityTo         = $event->DTO->cityTo;
        $self->pickup         = $event->DTO->pickup;
        $self->processing     = $event->DTO->processing;
        $self->transit        = $event->DTO->transit;
        $self->delivery       = $event->DTO->delivery;
        $self->shipmentTypeId = $event->DTO->tariff === 'standart' ? 1 : 2;

        return $self;
    }
}
