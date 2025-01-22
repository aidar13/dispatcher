<?php

declare(strict_types=1);

namespace App\Module\Order\Services;

use App\Module\Order\Contracts\Queries\OrderQuery;
use App\Module\Order\Contracts\Services\OrderService as OrderServiceContract;
use App\Module\Order\DTO\OrderProblemDTO;
use App\Module\Order\Models\Order;
use App\Module\Order\Services\Pipelines\Problems\Order\ChangeDatePipeline;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;

final readonly class OrderService implements OrderServiceContract
{
    public function __construct(
        private OrderQuery $query
    ) {
    }

    public function getById(int $orderId, array $columns = ['*'], array $relations = []): ?Order
    {
        return $this->query->getById($orderId, $columns, $relations);
    }

    public function getProblems(Order $order): Collection
    {
        /** @var OrderProblemDTO $result */
        $result = app(Pipeline::class)
            ->send(new OrderProblemDTO($order))
            ->through([
                ChangeDatePipeline::class,
            ])
            ->thenReturn();

        return $result->errors;
    }
}
