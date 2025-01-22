<?php

declare(strict_types=1);

namespace App\Module\Order\Services\Pipelines\Problems\Order;

use App\Module\Order\DTO\OrderProblemDTO;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\WaitListStatus;
use Exception;

final class ChangeDatePipeline
{
    /**
     * @throws Exception
     */
    public function handle(OrderProblemDTO $dto, \Closure $next)
    {
        /** @var WaitListStatus $changeDate */
        $changeDate = $dto->order->waitListStatuses
            ->where('code', RefStatus::CODE_CHANGED_TAKE_DATE)
            ->where('state_id', WaitListStatus::ID_CONFIRMED)
            ->first();

        if ($changeDate) {
            $dto->errors->add($changeDate->refStatus->name);
        }

        return $next($dto);
    }
}
