<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Queries;

use App\Module\Order\Models\FastDeliveryOrder;

interface FastDeliveryOrderQuery
{
    public function findByContainerId(int $containerId): FastDeliveryOrder;

    public function findByInternalId(int $internalId): FastDeliveryOrder;
}
