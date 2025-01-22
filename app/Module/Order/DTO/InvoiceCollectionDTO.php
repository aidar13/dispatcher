<?php

declare(strict_types=1);

namespace App\Module\Order\DTO;

use Illuminate\Support\Collection;

final class InvoiceCollectionDTO
{
    public ?Collection $invoices;

    /**
     * @psalm-suppress InvalidArgument
     */
    public static function fromArray(array $invoices): ?Collection
    {
        $self           = new self();
        $self->invoices = collect();

        foreach ($invoices as $invoice) {
            $self->invoices->push(InvoiceDTO::fromEvent($invoice));
        }

        return $self->invoices;
    }
}
