<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Repositories\Integration;

use App\Module\Order\Models\Invoice;

interface UpdateInvoiceSectorsRepository
{
    public function update(Invoice $invoice): void;
}
