<?php

declare(strict_types=1);

namespace App\Module\Order\Commands;

use App\Module\Order\DTO\InvoiceCargoDTO;

final class CreateInvoiceCargoCommand
{
    public function __construct(public InvoiceCargoDTO $DTO)
    {
    }
}
