<?php

declare(strict_types=1);

namespace App\Module\Order\DTO;

use App\Module\Order\Models\Invoice;
use Illuminate\Support\Collection;

final class InvoiceProblemDTO
{
    public Collection $errors;

    public function __construct(public Invoice $invoice)
    {
        $this->errors = collect();
    }
}
