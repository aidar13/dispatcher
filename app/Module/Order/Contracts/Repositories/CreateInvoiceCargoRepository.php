<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Repositories;

use App\Module\Order\Models\InvoiceCargo;

interface CreateInvoiceCargoRepository
{
    public function create(InvoiceCargo $invoiceInfo): void;
}
