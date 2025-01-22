<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Queries;

use App\Module\Order\Models\Sla;

interface SlaQuery
{
    public function getById(int $id): Sla;

    public function findSlaByCity(int $cityFrom, int $cityTo, int $shipmentType): ?Sla;
}
