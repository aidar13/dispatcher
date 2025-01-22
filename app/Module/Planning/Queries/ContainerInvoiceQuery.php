<?php

declare(strict_types=1);

namespace App\Module\Planning\Queries;

use App\Module\Planning\Contracts\Queries\ContainerInvoiceQuery as ContainerInvoiceQueryContract;
use App\Module\Planning\Models\ContainerInvoice;
use Illuminate\Database\Eloquent\Collection;

final class ContainerInvoiceQuery implements ContainerInvoiceQueryContract
{
    public function getLastInvoicePosition(int $containerId): int
    {
        /** @var ContainerInvoice|null $lastInvoice */
        $lastInvoice = ContainerInvoice::query()
            ->select('position')
            ->where('container_id', $containerId)
            ->orderByDesc('position')
            ->first();

        return (int)$lastInvoice?->position;
    }

    public function getByInvoiceIds(array $invoiceIds): Collection
    {
        /** @var Collection */
        return ContainerInvoice::query()
            ->whereIn('invoice_id', $invoiceIds)
            ->get();
    }

    public function findByContainerIdAndInvoiceId(int $containerId, int $invoiceId): ContainerInvoice
    {
        /** @var ContainerInvoice */
        return ContainerInvoice::query()
            ->where('container_id', $containerId)
            ->where('invoice_id', $invoiceId)
            ->firstOrFail();
    }

    public function getByContainerIdAndInvoiceNumbers(int $containerId, array $invoiceNumbers): Collection
    {
        /** @var Collection */
        return ContainerInvoice::query()
            ->with(['invoice:id,invoice_number'])
            ->where('container_id', $containerId)
            ->whereHas('invoice', fn($query) => $query->whereIn('invoice_number', $invoiceNumbers))
            ->get();
    }
}
