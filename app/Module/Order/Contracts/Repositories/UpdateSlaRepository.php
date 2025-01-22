<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Repositories;

use App\Module\Order\Models\Sla;

interface UpdateSlaRepository
{
    public function update(Sla $sla): void;
}
