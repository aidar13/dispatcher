<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Queries;

use App\Module\Order\Models\InvoiceCargo;

interface InvoiceCargoQuery
{
    public function getById(int $id, array $columns = ['*'], array $relations = []): ?InvoiceCargo;

    public function getByInvoiceId(int $invoiceId, array $columns = ['*'], array $relations = []): ?InvoiceCargo;
}
