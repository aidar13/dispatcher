<?php

declare(strict_types=1);

namespace App\Module\Car\Contracts\Repositories;

use App\Module\Car\Models\Car;

interface UpdateCarRepository
{
    public function update(Car $car): void;
}
