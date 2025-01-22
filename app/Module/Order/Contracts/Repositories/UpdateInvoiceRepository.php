<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Repositories;

use App\Module\Order\Models\Invoice;

interface UpdateInvoiceRepository
{
    public function update(Invoice $invoice): void;
}
