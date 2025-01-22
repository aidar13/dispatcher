<?php

declare(strict_types=1);

namespace App\Module\Delivery\Repositories\Eloquent;

use App\Module\Delivery\Contracts\Repositories\CreateDeliveryRepository;
use App\Module\Delivery\Contracts\Repositories\UpdateDeliveryRepository;
use App\Module\Delivery\Models\Delivery;
use Throwable;

final class DeliveryRepository implements CreateDeliveryRepository, UpdateDeliveryRepository
{
    /**
     * @throws Throwable
     */
    public function create(Delivery $delivery): void
    {
        $delivery->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    public function update(Delivery $delivery): void
    {
        $delivery->updateOrFail();
    }
}
