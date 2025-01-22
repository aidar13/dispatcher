<?php

declare(strict_types=1);

namespace App\Module\Delivery\DTO;

use App\Module\Take\DTO\CustomerDTO;

final class DeliveryDTO
{
    public ?int $internalId;
    public int $invoiceId;
    public ?string $invoiceNumber;
    public ?int $companyId;
    public int $cityId;
    public ?int $courierId;
    public ?int $statusId;
    public ?int $waitListStatusId;
    public ?int $places;
    public ?float $weight;
    public ?float $volume;
    public ?float $volumeWeight;
    public ?string $deliveryReceiverName;
    public ?string $courierComment;
    public ?string $deliveredAt;
    public ?string $createdAt;
    public ?string $routeSheetId;
    public CustomerDTO $customerDTO;

    public static function fromEvent($event): self
    {
        $self                       = new self();
        $self->internalId           = $event->DTO->id;
        $self->invoiceId            = $event->DTO->invoiceId;
        $self->invoiceNumber        = $event->DTO->invoiceNumber;
        $self->companyId            = $event->DTO->companyId;
        $self->cityId               = $event->DTO->cityId;
        $self->courierId            = $event->DTO->courierId;
        $self->statusId             = $event->DTO->status;
        $self->waitListStatusId     = $event->DTO->waitListStatusId;
        $self->places               = $event->DTO->places;
        $self->weight               = $event->DTO->weight;
        $self->volume               = $event->DTO->volume;
        $self->volumeWeight         = $event->DTO->volumeWeight;
        $self->deliveryReceiverName = $event->DTO->deliveryReceiverName;
        $self->courierComment       = $event->DTO->courierComment;
        $self->deliveredAt          = $event->DTO->deliveredAt;
        $self->createdAt            = $event->DTO->createdAt ?? null;
        $self->routeSheetId         = $event->DTO->routeSheetId;
        $self->customerDTO          = CustomerDTO::fromDeliveryEvent($event);

        return $self;
    }
}
