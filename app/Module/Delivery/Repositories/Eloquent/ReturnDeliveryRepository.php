<?php

declare(strict_types=1);

namespace App\Module\Delivery\Repositories\Eloquent;

use App\Module\Delivery\Contracts\Repositories\CreateReturnDeliveryRepository;
use App\Module\Delivery\Contracts\Repositories\DeleteReturnDeliveryRepository;
use App\Module\Delivery\Models\ReturnDelivery;
use Throwable;

final class ReturnDeliveryRepository implements CreateReturnDeliveryRepository, DeleteReturnDeliveryRepository
{
    /**
     * @throws Throwable
     */
    public function create(ReturnDelivery $model): void
    {
        $model->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    public function delete(ReturnDelivery $model): void
    {
        $model->deleteOrFail();
    }
}
