<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Repositories\Integration;

use App\Module\Order\DTO\CancelInvoiceDTO;

interface CancelInvoiceRepository
{
    public function cancel(CancelInvoiceDTO $DTO): void;
}
