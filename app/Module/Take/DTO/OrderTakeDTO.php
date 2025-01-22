<?php

declare(strict_types=1);

namespace App\Module\Take\DTO;

final class OrderTakeDTO
{
    public ?int $internalId = null;
    public int $invoiceId;
    public ?int $companyId;
    public int $cityId;
    public ?string $takeDate;
    public ?int $shipmentType;
    public ?int $places;
    public ?float $weight;
    public ?float $volume;
    public ?int $orderId;
    public ?string $orderNumber;
    public ?string $deletedAt = null;
    public CustomerDTO $customerDTO;

    public static function fromEvent($event): self
    {
        $self                   = new self();
        $self->internalId       = $event->DTO?->id ?? null;
        $self->invoiceId        = $event->DTO->invoiceId;
        $self->companyId        = $event->DTO->companyId;
        $self->cityId           = $event->DTO->cityId;
        $self->takeDate         = $event->DTO->takeDate;
        $self->shipmentType     = $event->DTO->shipmentType;
        $self->places           = $event->DTO->places;
        $self->weight           = $event->DTO->weight;
        $self->volume           = $event->DTO->volume;
        $self->customerDTO      = CustomerDTO::fromOrderTakeEvent($event);
        $self->orderId          = $event->DTO->orderId;
        $self->orderNumber      = $event->DTO->orderNumber;
        $self->deletedAt        = $event->DTO->deletedAt;

        return $self;
    }
}
