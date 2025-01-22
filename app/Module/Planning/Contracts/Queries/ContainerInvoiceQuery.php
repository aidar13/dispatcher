<?php

declare(strict_types=1);

namespace App\Module\Planning\Contracts\Queries;

use App\Module\Planning\Models\ContainerInvoice;
use Illuminate\Database\Eloquent\Collection;

interface ContainerInvoiceQuery
{
    public function getLastInvoicePosition(int $containerId): int;

    public function getByInvoiceIds(array $invoiceIds): Collection;

    public function getByContainerIdAndInvoiceNumbers(int $containerId, array $invoiceNumbers): Collection;

    public function findByContainerIdAndInvoiceId(int $containerId, int $invoiceId): ContainerInvoice;
}
