<?php

declare(strict_types=1);

namespace App\Module\Car\Queries;

use App\Module\Car\Contracts\Queries\CarQuery as CarQueryContract;
use App\Module\Car\Models\Car;

final class CarQuery implements CarQueryContract
{
    public function getById(int $id): Car
    {
        /** @var Car */
        return Car::query()->findOrFail($id);
    }
}
