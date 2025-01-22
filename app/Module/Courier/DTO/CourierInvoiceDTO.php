<?php

declare(strict_types=1);

namespace App\Module\Courier\DTO;

use App\Module\Order\Models\Invoice;
use Illuminate\Support\Collection;

final class CourierInvoiceDTO
{
    public Collection $errors;

    public function __construct(public readonly Collection|Invoice $invoices)
    {
        $this->errors = collect();
    }
}
