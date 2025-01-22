<?php

declare(strict_types=1);

namespace App\Module\Delivery\Contracts\Queries;

use App\Module\Delivery\DTO\RouteSheetIndexDTO;
use App\Module\Delivery\Models\RouteSheet;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RouteSheetQuery
{
    public function getById(int $id): RouteSheet;

    public function getWithInfosById(int $id): RouteSheet;

    public function getByInvoiceId(int $invoiceId): RouteSheet;

    public function getByRouteSheetNumber(string $number): ?RouteSheet;

    public function getAllPaginated(RouteSheetIndexDTO $DTO): LengthAwarePaginator;
}
