<?php

declare(strict_types=1);

namespace App\Module\Gateway\Contracts\Integration;

interface SendToCabinetRepository
{
    public function assignOrderTakes(array $orderIds, int $courierId, bool $storeOrderStatus): void;
}
