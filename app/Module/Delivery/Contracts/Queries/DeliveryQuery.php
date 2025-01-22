<?php

declare(strict_types=1);

namespace App\Module\Delivery\Contracts\Queries;

use App\Module\Delivery\DTO\DeliveryReportDTO;
use App\Module\Delivery\DTO\DeliveryShowDTO;
use App\Module\Delivery\Models\Delivery;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface DeliveryQuery
{
    public function getAllPaginated(DeliveryShowDTO $DTO): LengthAwarePaginator;

    public function getAllCollection(DeliveryReportDTO $DTO, array $columns = ['*'], array $relations = []): Collection;
    public function getByInvoiceNumberAndVerify(string $invoiceNumber, int $verify): Collection;
    public function getByInternalId(int $id): ?Delivery;

    public function getByInvoiceId(int $id, string $sortDir = 'asc'): ?Delivery;

    public function getAllByInvoiceId(int $id, array $columns = ['*']): EloquentCollection;

    public function getById(int $id): Delivery;

    public function getByDispatcherSectorAndCreatedInterval(
        ?int $dispatcherSectorId,
        string $createdAtFrom,
        string $createdAtTo,
        array $columns = ['*'],
        array $relations = []
    ): Collection;
}
