<?php

declare(strict_types=1);

namespace App\Module\Order\Queries;

use App\Module\Order\Contracts\Queries\InvoiceCargoQuery as InvoiceCargoQueryContract;
use App\Module\Order\Models\InvoiceCargo;

final class InvoiceCargoQuery implements InvoiceCargoQueryContract
{
    public function getById(int $id, array $columns = ['*'], array $relations = []): ?InvoiceCargo
    {
        /** @var InvoiceCargo */
        return InvoiceCargo::query()
            ->select($columns)
            ->with($relations)
            ->find($id);
    }

    public function getByInvoiceId(int $invoiceId, array $columns = ['*'], array $relations = []): ?InvoiceCargo
    {
        /** @var InvoiceCargo|null */
        return InvoiceCargo::query()
            ->select($columns)
            ->where('invoice_id', $invoiceId)
            ->with($relations)
            ->first();
    }
}
