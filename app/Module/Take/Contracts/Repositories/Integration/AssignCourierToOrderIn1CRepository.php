<?php

declare(strict_types=1);

namespace App\Module\Take\Contracts\Repositories\Integration;

use App\Module\Order\Models\Invoice;

interface AssignCourierToOrderIn1CRepository
{
    public function assignCourierToOrder(Invoice $invoice, int $courierId, ?string $orderNumber): void;
}
