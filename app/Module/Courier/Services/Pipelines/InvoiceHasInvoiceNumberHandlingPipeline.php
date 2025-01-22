<?php

declare(strict_types=1);

namespace App\Module\Courier\Services\Pipelines;

use App\Module\Courier\DTO\CourierInvoiceDTO;
use App\Module\Order\Models\Invoice;
use Illuminate\Support\Collection;

final class InvoiceHasInvoiceNumberHandlingPipeline
{
    public function __construct(private readonly Collection $orderNumbers)
    {
    }

    /**
     * @psalm-suppress UndefinedMagicMethod
     */
    public function handle(CourierInvoiceDTO $dto, \Closure $next)
    {
        /** @var Invoice $invoice */
        foreach ($dto->invoices as $invoice) {
            if (!$invoice->invoice_number) {
                $this->orderNumbers->add($invoice->order->number);
            }
        }

        if ($this->orderNumbers->isNotEmpty()) {
            $dto->errors->add(__('validation.courier_close_day.invoice_number', ['orderNumbers' => $this->orderNumbers->implode(', ')]));
        }

        return $next($dto);
    }
}
