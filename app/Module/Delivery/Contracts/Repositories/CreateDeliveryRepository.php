<?php

declare(strict_types=1);

namespace App\Module\Delivery\Contracts\Repositories;

use App\Module\Delivery\Models\Delivery;

interface CreateDeliveryRepository
{
    public function create(Delivery $delivery): void;
}
