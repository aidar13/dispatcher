<?php

declare(strict_types=1);

namespace App\Module\Take\DTO;

final class CustomerDTO
{
    public ?string $address;
    public ?string $fullName;
    public ?string $phone;
    public ?string $additionalPhone;
    public ?string $latitude;
    public ?string $longitude;

    public static function fromOrderTakeEvent($event): self
    {
        $self                     = new self();
        $self->address            = $event->DTO?->fullAddress ?? null;
        $self->fullName           = $event->DTO?->fullName ?? null;
        $self->phone              = $event->DTO?->phone ?? null;
        $self->additionalPhone    = $event->DTO?->additionalPhone ?? null;
        $self->latitude           = $event->DTO?->latitude ?? null;
        $self->longitude          = $event->DTO?->longitude ?? null;

        return $self;
    }

    public static function fromDeliveryEvent($event): self
    {
        $self                  = new self();
        $self->address         = $event->DTO?->receiverFullAddress ?? null;
        $self->fullName        = $event->DTO?->receiverFullName ?? null;
        $self->phone           = $event->DTO?->receiverPhone ?? null;
        $self->additionalPhone = $event->DTO?->additionalPhone ?? null;
        $self->latitude        = $event->DTO?->latitude ?? null;
        $self->longitude       = $event->DTO?->longitude ?? null;

        return $self;
    }
}
