<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Repositories;

use App\Module\Order\Models\InvoiceCargo;

interface UpdateInvoiceCargoRepository
{
    public function update(InvoiceCargo $invoiceInfo): void;
}
