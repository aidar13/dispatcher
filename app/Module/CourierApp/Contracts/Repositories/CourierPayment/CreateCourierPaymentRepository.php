<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Contracts\Repositories\CourierPayment;

use App\Module\CourierApp\Models\CourierPayment;

interface CreateCourierPaymentRepository
{
    public function create(CourierPayment $model): void;
}
