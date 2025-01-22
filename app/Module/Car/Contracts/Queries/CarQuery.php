<?php

declare(strict_types=1);

namespace App\Module\Car\Contracts\Queries;

use App\Module\Car\Models\Car;

interface CarQuery
{
    public function getById(int $id): Car;
}
