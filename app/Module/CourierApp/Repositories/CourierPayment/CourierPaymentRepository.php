<?php

namespace App\Module\CourierApp\Repositories\CourierPayment;

use App\Module\CourierApp\Contracts\Repositories\CourierPayment\CreateCourierPaymentRepository;
use App\Module\CourierApp\Models\CourierPayment;

class CourierPaymentRepository implements CreateCourierPaymentRepository
{
    public function create(CourierPayment $model): void
    {
        $model->saveOrFail();
    }
}
