<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Repositories;

use App\Module\Order\Models\Sla;

interface CreateSlaRepository
{
    public function create(Sla $sla): void;
}
