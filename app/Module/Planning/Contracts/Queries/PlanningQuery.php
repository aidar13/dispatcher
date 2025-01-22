<?php

declare(strict_types=1);

namespace App\Module\Planning\Contracts\Queries;

use App\Module\Planning\DTO\PlanningShowDTO;
use App\Module\Planning\DTO\SectorInvoiceDTO;
use Illuminate\Database\Eloquent\Collection;

interface PlanningQuery
{
    public function getSectors(PlanningShowDTO $DTO): Collection;

    public function getSectorInvoices(SectorInvoiceDTO $DTO): Collection;

    public function getInvoicesForRoutingByDispatcherSectorId(int $id, string $date): Collection;
}
