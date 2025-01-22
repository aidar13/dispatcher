<?php

declare(strict_types=1);

namespace App\Module\Order\DTO\Integration;

use Illuminate\Support\Arr;

final class FastDeliveryOrderDTO
{
    public ?int $internalOrderId = null;
    public ?string $price = null;
    public ?string $internalStatus = null;
    public ?string $trackingUrl = null;

    public static function fromArray(array $data): self
    {
        $self                  = new self();
        $self->internalOrderId = (int)Arr::get($data, 'id') ?: null;
        $self->price           = Arr::get($data, 'price') ?: null;
        $self->internalStatus  = Arr::get($data, 'internalStatus') ?: null;
        $self->trackingUrl     = Arr::get($data, 'trackingUrl') ?: null;

        return $self;
    }
}
