<?php

declare(strict_types=1);

namespace App\Module\Courier\Contracts\Repositories;

use App\Module\Courier\Models\Courier;

interface UpdateCourierRepository
{
    public function update(Courier $courier): void;
}
