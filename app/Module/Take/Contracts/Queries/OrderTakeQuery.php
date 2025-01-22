<?php

declare(strict_types=1);

namespace App\Module\Take\Contracts\Queries;

use App\Module\Monitoring\DTO\TakeInfoShowDTO;
use App\Module\Take\DTO\OrderTakeShowDTO;
use App\Module\Take\Models\OrderTake;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface OrderTakeQuery
{
    public function getAllPaginated(OrderTakeShowDTO $DTO): LengthAwarePaginator;

    public function getAllForExport(OrderTakeShowDTO $DTO, array $columns = ['*'], array $relations = []): Collection;

    public function getByInternalId(int $id): ?OrderTake;

    public function getByOrderId(int $orderId): ?Collection;

    public function getByInvoiceId(int $invoiceId): ?OrderTake;

    public function getAllByInvoiceId(int $invoiceId): Collection;

    public function getByInvoiceNumbers(array $invoiceNumbers, array $columns = ['*'], array $relations = []): Collection;

    public function getById(int $id): OrderTake;

    public function getByDispatcherSectorAndCreatedInterval(TakeInfoShowDTO $DTO, array $columns = ['*'], array $relations = []): Collection;

    public function getLastHourCourierTakesByFullAddress(int $courierId, string $receiverFullAddress): Collection;
}
