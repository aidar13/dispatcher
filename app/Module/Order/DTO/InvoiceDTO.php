<?php

declare(strict_types=1);

namespace App\Module\Order\DTO;

use App\Helpers\CargoHelper;
use Illuminate\Support\Carbon;

final class InvoiceDTO
{
    public int $id;
    public ?string $invoiceNumber;
    public int $orderId;
    public ?int $statusId;
    public int $receiverId;
    public ?string $code1c;
    public ?string $dopInvoiceNumber;
    public ?string $takeDate;
    public ?string $takeTime;
    public ?int $paymentType;
    public ?int $paymentMethod;
    public int $directionId;
    public int $shipmentId;
    public ?int $periodId;
    public ?float $cashSum;
    public int $shouldReturnDocument;
    public int $verify;
    public ?int $type;
    public ?int $weekendDelivery;
    public ?int $payerCompanyId;
    public ?int $cargoType;
    public Carbon|null $createdAt;
    public InvoiceCargoDTO $invoiceCargo;

    public static function fromEvent($event): self
    {
        $self                       = new self();
        $self->id                   = (int)$event->id;
        $self->invoiceNumber        = $event->invoiceNumber;
        $self->orderId              = (int)$event->orderId;
        $self->receiverId           = (int)$event->receiverId;
        $self->statusId             = $event->statusId;
        $self->code1c               = $event->code1c;
        $self->dopInvoiceNumber     = $event->dopInvoiceNumber;
        $self->takeDate             = $event->takeDate;
        $self->takeTime             = $event->takeTime;
        $self->paymentType          = $event->paymentType;
        $self->paymentMethod        = $event->paymentMethod;
        $self->periodId             = $event->periodId;
        $self->shipmentId           = (int)$event->shipmentType;
        $self->directionId          = (int)$event->directionId;
        $self->cashSum              = (float)$event->cashSum;
        $self->shouldReturnDocument = $event->shouldReturnDocument;
        $self->verify               = (int)$event->verify;
        $self->type                 = $event->type;
        $self->weekendDelivery      = $event->weekendDelivery;
        $self->createdAt            = Carbon::make($event->createdAt);
        $self->payerCompanyId       = $event->payerCompanyId ?: null;
        $self->invoiceCargo         = InvoiceCargoDTO::fromEvent($event);
        $self->cargoType            = CargoHelper::getTypeFromCargoDTO($self->invoiceCargo);

        return $self;
    }
}
