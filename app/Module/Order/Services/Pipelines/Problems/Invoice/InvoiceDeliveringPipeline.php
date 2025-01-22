<?php

declare(strict_types=1);

namespace App\Module\Order\Services\Pipelines\Problems\Invoice;

use App\Module\Order\DTO\InvoiceProblemDTO;
use App\Module\Status\Models\RefStatus;

final class InvoiceDeliveringPipeline
{
    public function handle(InvoiceProblemDTO $dto, \Closure $next)
    {
        $delivery = $dto->invoice->getStatusByCode(RefStatus::CODE_DELIVERY_IN_PROGRESS, true);

        if (!$delivery) {
            return $next($dto);
        }

        $delivered      = $dto->invoice->getStatusByCode(RefStatus::CODE_DELIVERED, true);
        $courierReturn  = $dto->invoice->getStatusByCode(RefStatus::CODE_COURIER_RETURN_DELIVERY, true);
        $returnDelivery = $dto->invoice->getStatusByCode(RefStatus::CODE_RETURN_DELIVERY, true);

        if (
            $delivered ||
            $returnDelivery ||
            ($courierReturn && $delivery->created_at <= $courierReturn->created_at)
        ) {
            return $next($dto);
        }

        $dto->errors->add('Остался в машине');

        return $next($dto);
    }
}
