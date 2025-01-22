<?php

declare(strict_types=1);

namespace App\Module\Order\Queries;

use App\Module\Order\Contracts\Queries\FastDeliveryOrderQuery as FastDeliveryOrderQueryContract;
use App\Module\Order\Models\FastDeliveryOrder;

final class FastDeliveryOrderQuery implements FastDeliveryOrderQueryContract
{
    public function findByContainerId(int $containerId): FastDeliveryOrder
    {
        /** @var FastDeliveryOrder */
        return FastDeliveryOrder::query()
            ->where('container_id', $containerId)
            ->firstOrFail();
    }

    public function findByInternalId(int $internalId): FastDeliveryOrder
    {
        /** @var FastDeliveryOrder */
        return FastDeliveryOrder::query()
            ->where('internal_id', $internalId)
            ->firstOrFail();
    }
}
