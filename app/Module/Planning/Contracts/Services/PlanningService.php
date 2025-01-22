<?php

declare(strict_types=1);

namespace App\Module\Planning\Contracts\Services;

use App\Module\Planning\DTO\PlanningShowDTO;
use Illuminate\Support\Collection;

interface PlanningService
{
    public function getSectors(PlanningShowDTO $DTO): Collection;
}
