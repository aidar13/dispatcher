<?php

declare(strict_types=1);

namespace App\Module\Courier\DTO\Integration;

final class CourierStopDTO
{
    public int $id;
    public int $courierId;
    public int $clientId;
    public string $clientType;

    public static function fromEvent($event): self
    {
        $self             = new self();
        $self->id         = (int)$event->id;
        $self->courierId  = (int)$event->courierId;
        $self->clientId   = (int)$event->clientId;
        $self->clientType = $event->clientType;

        return $self;
    }
}
