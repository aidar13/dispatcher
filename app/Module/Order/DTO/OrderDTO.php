<?php

declare(strict_types=1);

namespace App\Module\Order\DTO;

use Illuminate\Support\Carbon;

final class OrderDTO
{
    public int $id;
    public int $companyId;
    public ?string $number;
    public int $senderId;
    public int $userId;
    public ?string $source;
    public ?int $parentId;
    public Carbon|null $createdAt;

    public static function fromEvent($event): self
    {
        $self            = new self();
        $self->id        = $event->id;
        $self->companyId = $event->companyId;
        $self->number    = $event->number;
        $self->senderId  = $event->senderId;
        $self->userId    = $event->userId;
        $self->source    = $event->source;
        $self->parentId  = $event->parentId;
        $self->createdAt = Carbon::make($event->createdAt);

        return $self;
    }
}
