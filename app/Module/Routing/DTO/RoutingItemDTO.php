<?php

declare(strict_types=1);

namespace App\Module\Routing\DTO;

use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\Order;
use App\Module\Routing\Models\RoutingItem;
use App\Module\Take\Models\OrderTake;

final class RoutingItemDTO
{
    public int $id;
    public int $invoiceId;
    public int $orderId;
    public int $clientId;
    public string $clientType;
    public int $type;
    public ?string $invoiceNumber;
    public ?string $orderNumber;

    public static function fromTake(OrderTake $take): self
    {
        $self = new self();

        $self->id            = $take->id;
        $self->invoiceId     = $take->invoice_id;
        $self->orderId       = $take->order_id;
        $self->orderNumber   = $take->order->number;
        $self->invoiceNumber = $take->invoice?->invoice_number;
        $self->type          = RoutingItem::TYPE_TAKE;
        $self->clientId      = $take->order_id;
        $self->clientType    = Order::class;

        return $self;
    }
    public static function fromInvoice(Invoice $invoice): self
    {
        $self = new self();

        $self->id            = $invoice->id;
        $self->invoiceId     = $invoice->id;
        $self->orderId       = $invoice->order_id;
        $self->orderNumber   = $invoice->order?->number;
        $self->invoiceNumber = $invoice->invoice_number;
        $self->type          = RoutingItem::TYPE_DELIVERY;
        $self->clientId      = $invoice->id;
        $self->clientType    = Invoice::class;

        return $self;
    }
}
