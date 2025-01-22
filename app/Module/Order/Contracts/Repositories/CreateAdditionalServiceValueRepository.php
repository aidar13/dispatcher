<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Repositories;

use App\Module\Order\Models\AdditionalServiceValue;

interface CreateAdditionalServiceValueRepository
{
    public function create(AdditionalServiceValue $service): void;
}
