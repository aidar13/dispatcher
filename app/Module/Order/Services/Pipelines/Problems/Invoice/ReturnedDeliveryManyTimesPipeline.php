<?php

declare(strict_types=1);

namespace App\Module\Order\Services\Pipelines\Problems\Invoice;

use App\Module\Order\DTO\InvoiceProblemDTO;
use App\Module\Status\Models\RefStatus;

final class ReturnedDeliveryManyTimesPipeline
{
    public function handle(InvoiceProblemDTO $dto, \Closure $next)
    {
        $courierReturns = $dto->invoice->statuses
            ->where('code', RefStatus::CODE_COURIER_RETURN_DELIVERY);

        if ($courierReturns->isNotEmpty()) {
            $dto->errors->add('Несколько ВВ');
        }

        return $next($dto);
    }
}
