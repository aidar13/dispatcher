<?php

declare(strict_types=1);

namespace App\Module\Planning\DTO;

final class ContainerInvoiceInfoDTO
{
    public string $invoiceNumber;
    public int $invoiceStatusId;
    public int $placesQuantity;

    public static function fromArray(array $invoice): self
    {
        $self                  = new self();
        $self->invoiceNumber   = $invoice['invoiceNumber'];
        $self->invoiceStatusId = (int)$invoice['invoiceStatusId'];
        $self->placesQuantity  = (int)$invoice['placesQuantity'];

        return $self;
    }
}
