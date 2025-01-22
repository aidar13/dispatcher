<?php

declare(strict_types=1);

namespace App\Module\Take\Services;

use App\Module\Take\Contracts\Queries\OrderPeriodQuery;
use App\Module\Take\Contracts\Services\OrderPeriodService as OrderPeriodServiceContract;
use App\Module\Take\DTO\OrderPeriodDTO;
use Illuminate\Database\Eloquent\Collection;

final class OrderPeriodService implements OrderPeriodServiceContract
{
    public function __construct(
        private readonly OrderPeriodQuery $query
    ) {
    }

    public function getAll(OrderPeriodDTO $DTO): Collection|array
    {
        return $this->query->getAll($DTO);
    }
}
