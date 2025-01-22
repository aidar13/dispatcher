<?php

declare(strict_types=1);

namespace App\Module\Delivery\Contracts\Repositories;

use App\Module\Delivery\Models\ReturnDelivery;

interface DeleteReturnDeliveryRepository
{
    public function delete(ReturnDelivery $model): void;
}
