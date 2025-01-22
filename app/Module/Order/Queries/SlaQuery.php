<?php

declare(strict_types=1);

namespace App\Module\Order\Queries;

use App\Module\Order\Contracts\Queries\SlaQuery as SlaQueryContract;
use App\Module\Order\Models\Sla;

final class SlaQuery implements SlaQueryContract
{
    public function getById(int $id): Sla
    {
        /** @var Sla */
        return Sla::query()->findOrFail($id);
    }

    public function findSlaByCity(int $cityFrom, int $cityTo, int $shipmentType): ?Sla
    {
        /** @var Sla|null */
        return Sla::query()
            ->where('shipment_type_id', $shipmentType)
            ->where('city_from', $cityFrom)
            ->where('city_to', $cityTo)
            ->first();
    }
}
