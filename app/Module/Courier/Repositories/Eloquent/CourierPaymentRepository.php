<?php

declare(strict_types=1);

namespace App\Module\Courier\Repositories\Eloquent;

use App\Module\Courier\Contracts\Repositories\CreateCourierPaymentRepository;
use App\Module\CourierApp\Models\CourierPayment;

final class CourierPaymentRepository implements CreateCourierPaymentRepository
{
    /**
     * @throws \Throwable
     */
    public function create(CourierPayment $model): void
    {
        $model->saveOrFail();
    }
}
