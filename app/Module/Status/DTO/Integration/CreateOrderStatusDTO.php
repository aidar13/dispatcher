<?php

declare(strict_types=1);

namespace App\Module\Status\DTO\Integration;

use Illuminate\Support\Carbon;

final class CreateOrderStatusDTO
{
    public int $id;
    public ?int $invoiceId;
    public ?string $invoiceNumber;
    public ?int $orderId;
    public ?int $code;
    public ?string $title;
    public ?string $comment;
    public ?int $sourceId;
    public ?int $userId;
    public Carbon|null $createdAt;

    public static function fromEvent($event): self
    {
        $self                = new self();
        $self->id            = $event->DTO->id;
        $self->invoiceId     = $event->DTO->invoiceId;
        $self->invoiceNumber = $event->DTO->invoiceNumber;
        $self->orderId       = $event->DTO->orderId;
        $self->code          = $event->DTO->code;
        $self->title         = $event->DTO->originalStatus;
        $self->comment       = $event->DTO->comment;
        $self->sourceId      = $event->DTO->sourceId;
        $self->userId        = $event->DTO->userId;
        $self->createdAt     = new Carbon($event->DTO->createdAt);

        return $self;
    }
}
