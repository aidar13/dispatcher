<?php

declare(strict_types=1);

namespace App\Module\Courier\Services\Pipelines;

use App\Module\Courier\DTO\CourierInvoiceDTO;
use App\Module\Order\Models\Invoice;
use App\Module\Status\Models\RefStatus;
use Illuminate\Support\Collection;

final class InvoicesDeliveredHandlingPipeline
{
    public function __construct(
        private readonly Collection $invoiceNumbers,
    ) {
    }

    /**
     * @psalm-suppress UndefinedMagicMethod
     */
    public function handle(CourierInvoiceDTO $dto, \Closure $next)
    {
        /** @var Invoice $invoice */
        foreach ($dto->invoices as $invoice) {
            if (
                $invoice->deliveries->isNotEmpty() and
                !$invoice->statuses->where('code', RefStatus::CODE_DELIVERED)->first() and
                !$invoice->statuses->whereIn('code', array_keys(RefStatus::WAIT_LIST_STATUSES))->first()
            ) {
                $this->invoiceNumbers->add($invoice->invoice_number);
            }
        }

        if ($this->invoiceNumbers->isNotEmpty()) {
            $dto->errors->add(__('validation.courier_close_day.not_delivered', ['invoiceNumbers' => $this->invoiceNumbers->implode(', ')]));
        }

        return $next($dto);
    }
}
