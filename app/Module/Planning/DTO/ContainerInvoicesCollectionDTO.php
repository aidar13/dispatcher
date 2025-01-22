<?php

declare(strict_types=1);

namespace App\Module\Planning\DTO;

use Illuminate\Support\Collection;

final class ContainerInvoicesCollectionDTO
{
    /**
     * @psalm-suppress InvalidArgument
     * @param array $invoices
     * @return Collection
     */
    public static function fromArray(array $invoices): Collection
    {
        $invoiceCollection = collect();

        foreach ($invoices as $invoice) {
            $invoiceCollection->push(ContainerInvoiceInfoDTO::fromArray($invoice));
        }

        return $invoiceCollection;
    }
}
