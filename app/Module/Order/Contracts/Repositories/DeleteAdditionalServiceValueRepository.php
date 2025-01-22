<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Repositories;

use App\Module\Order\Models\AdditionalServiceValue;

interface DeleteAdditionalServiceValueRepository
{
    public function remove(AdditionalServiceValue $service): void;
}
