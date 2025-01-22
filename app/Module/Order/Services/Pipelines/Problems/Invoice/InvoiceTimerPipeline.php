<?php

declare(strict_types=1);

namespace App\Module\Order\Services\Pipelines\Problems\Invoice;

use App\Module\Order\DTO\InvoiceProblemDTO;
use App\Module\Status\Models\RefStatus;
use Exception;

final class InvoiceTimerPipeline
{
    /**
     * @throws Exception
     */
    public function handle(InvoiceProblemDTO $dto, \Closure $next)
    {
        if ($dto->invoice->isCurrentStatus(RefStatus::ID_DELIVERED)) {
            return $next($dto);
        }

        $timer = $dto->invoice->getTimerMinutes();

        if ($timer < 0) {
            $dto->errors->add('Опаздывает');
        }

        return $next($dto);
    }
}
