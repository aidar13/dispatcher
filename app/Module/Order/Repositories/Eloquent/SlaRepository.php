<?php

declare(strict_types=1);

namespace App\Module\Order\Repositories\Eloquent;

use App\Module\Order\Contracts\Repositories\CreateSlaRepository;
use App\Module\Order\Contracts\Repositories\UpdateSlaRepository;
use App\Module\Order\Models\Sla;

final class SlaRepository implements CreateSlaRepository, UpdateSlaRepository
{
    public function create(Sla $sla): void
    {
        $sla->save();
    }

    public function update(Sla $sla): void
    {
        $sla->updateOrFail();
    }
}
