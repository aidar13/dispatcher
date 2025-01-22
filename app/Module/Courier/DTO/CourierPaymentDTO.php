<?php

declare(strict_types=1);

namespace App\Module\Courier\DTO;

use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\Order;

final class CourierPaymentDTO
{
    public int $id;
    public int $courierId;
    public int $clientId;
    public string $clientType;
    public int $type;
    public int $cost;

    public static function fromEvent($event): self
    {
        $self             = new self();
        $self->id         = $event->DTO->id;
        $self->courierId  = $event->DTO->courierId;
        $self->clientId   = $event->DTO->clientId;
        $self->clientType = str_contains($event->DTO->clientType, 'OrderLogisticsInfo')
                            ? Invoice::class
                            : Order::class;
        $self->type       = $event->DTO->type;
        $self->cost       = $event->DTO->cost;

        return $self;
    }
}
