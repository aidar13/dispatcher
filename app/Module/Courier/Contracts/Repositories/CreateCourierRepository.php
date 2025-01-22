<?php

declare(strict_types=1);

namespace App\Module\Courier\Contracts\Repositories;

use App\Module\Courier\Models\Courier;

interface CreateCourierRepository
{
    public function create(Courier $courier): void;
}
