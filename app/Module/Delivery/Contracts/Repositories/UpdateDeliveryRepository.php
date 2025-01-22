<?php

declare(strict_types=1);

namespace App\Module\Delivery\Contracts\Repositories;

use App\Module\Delivery\Models\Delivery;

interface UpdateDeliveryRepository
{
    public function update(Delivery $delivery): void;
}
