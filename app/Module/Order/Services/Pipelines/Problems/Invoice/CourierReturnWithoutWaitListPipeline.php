<?php

declare(strict_types=1);

namespace App\Module\Order\Services\Pipelines\Problems\Invoice;

use App\Module\Order\DTO\InvoiceProblemDTO;
use App\Module\Status\Models\OrderStatus;
use App\Module\Status\Models\RefStatus;

final class CourierReturnWithoutWaitListPipeline
{
    public function handle(InvoiceProblemDTO $dto, \Closure $next)
    {
        $returnDelivery = $dto->invoice->getStatusByCode(RefStatus::CODE_COURIER_RETURN_DELIVERY, true);

        if (!$returnDelivery) {
            return $next($dto);
        }

        /** @var OrderStatus|null $delivery */
        $delivery = $dto->invoice->statuses
            ->filter(function (OrderStatus $status) use ($returnDelivery) {
                return $status->code == RefStatus::CODE_DELIVERY_IN_PROGRESS &&
                    $status->created_at < $returnDelivery->created_at;
            })->first();

        $waitList = $dto->invoice->statuses
            ->filter(function (OrderStatus $status) use ($returnDelivery, $delivery) {
                return
                    in_array($status->code, RefStatus::WAITING_LIST_CODES) &&
                    $status->created_at > $delivery?->created_at &&
                    $status->created_at < $returnDelivery->created_at;
            })->first();

        if (!$waitList) {
            $dto->errors->add('ВВ без ЛО');
        }

        return $next($dto);
    }
}
