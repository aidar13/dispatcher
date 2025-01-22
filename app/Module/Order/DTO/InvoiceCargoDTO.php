<?php

declare(strict_types=1);

namespace App\Module\Order\DTO;

use Illuminate\Support\Carbon;

final class InvoiceCargoDTO
{
    public int $invoiceId;
    public ?string $cargoName;
    public ?string $productName;
    public ?int $places;
    public ?float $weight;
    public ?float $volume;
    public ?float $volumeWeight;
    public ?float $width;
    public ?float $height;
    public ?float $depth;
    public ?int $codPayment;
    public ?string $annotation;
    public ?string $cargoPackCode;
    public ?string $cargoSizeType;
    public Carbon|null $createdAt;

    public static function fromEvent($event): self
    {
        $self                = new self();
        $self->invoiceId     = $event->id;
        $self->cargoName     = $event->cargoName;
        $self->productName   = $event->productName;
        $self->places        = $event->places;
        $self->weight        = $event->weight;
        $self->volume        = $event->volume;
        $self->volumeWeight  = $event->volumeWeight;
        $self->width         = $event->width;
        $self->height        = $event->height;
        $self->codPayment    = (int)$event->codPayment;
        $self->depth         = $event->depth;
        $self->annotation    = $event->annotation;
        $self->cargoSizeType = $event->cargoSizeType;
        $self->cargoPackCode = $event->cargoPackCode;
        $self->createdAt     = Carbon::make($event->createdAt);

        return $self;
    }
}
