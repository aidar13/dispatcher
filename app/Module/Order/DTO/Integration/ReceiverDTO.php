<?php

declare(strict_types=1);

namespace App\Module\Order\DTO\Integration;

use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\Receiver;

final class ReceiverDTO
{
    public int $receiverId;
    public string $fullName;
    public string $phone;
    public ?string $additionalPhone;
    public ?string $house;
    public ?string $street;
    public string $fullAddress;
    public string $longitude;
    public string $latitude;
    public string $invoiceNumber;
    public int $verify;
    public array $cargo;

    public static function fromModel(Receiver $receiver, Invoice $invoice): self
    {
        $self                  = new self();
        $self->receiverId      = $receiver->id;
        $self->fullName        = $receiver->full_name;
        $self->phone           = $receiver->phone;
        $self->additionalPhone = $receiver->additional_phone;
        $self->house           = $receiver->house;
        $self->street          = $receiver->street;
        $self->fullAddress     = $receiver->full_address;
        $self->longitude       = $receiver->longitude;
        $self->latitude        = $receiver->latitude;
        $self->invoiceNumber   = $invoice->invoice_number;
        $self->verify          = $invoice->verify;
        $self->cargo           = [
            'weight' => $invoice->cargo->weight,
            'height' => $invoice->cargo->height,
            'depth'  => $invoice->cargo->depth,
            'width'  => $invoice->cargo->width,
        ];

        return $self;
    }
}
