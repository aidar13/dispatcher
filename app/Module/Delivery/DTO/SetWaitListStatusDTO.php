<?php

declare(strict_types=1);

namespace App\Module\Delivery\DTO;

final class SetWaitListStatusDTO
{
    public ?int $statusId = null;
    public int $internalId;

    public static function fromEvent($event): self
    {
        $self             = new self();
        $self->statusId   = $event->statusId;
        $self->internalId = (int)$event->internalId;
        return $self;
    }
}
