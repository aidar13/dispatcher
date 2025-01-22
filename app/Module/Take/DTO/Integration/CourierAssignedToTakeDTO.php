<?php

declare(strict_types=1);

namespace App\Module\Take\DTO\Integration;

use App\Module\Order\Models\Order;

final class CourierAssignedToTakeDTO
{
    public int $orderId;
    public ?string $orderNumber;
    public ?int $courierUserId;
    public ?string $fullAddress;

    public static function fromOrder(Order $order): self
    {
        $self                = new self();
        $self->orderId       = $order->id;
        $self->orderNumber   = $order->number;
        $self->courierUserId = $order->invoices->first()->take?->courier?->user_id;
        $self->fullAddress   = $order->sender?->full_address;

        return $self;
    }
}
