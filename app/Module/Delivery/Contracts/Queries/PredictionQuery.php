<?php

declare(strict_types=1);

namespace App\Module\Delivery\Contracts\Queries;

use App\Module\Delivery\DTO\PredictionDTO;
use Illuminate\Database\Eloquent\Collection;

interface PredictionQuery
{
    public function getReport(PredictionDTO $DTO): Collection;
}
