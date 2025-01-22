<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Repositories;

use App\Module\Order\Models\Invoice;

interface CreateInvoiceRepository
{
    public function create(Invoice $invoice): void;
}
