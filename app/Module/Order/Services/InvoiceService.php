<?php

declare(strict_types=1);

namespace App\Module\Order\Services;

use App\Module\Order\Contracts\Queries\InvoiceQuery;
use App\Module\Order\Contracts\Services\InvoiceService as InvoiceServiceContract;
use App\Module\Order\DTO\InvoiceProblemDTO;
use App\Module\Order\DTO\InvoicesDTO;
use App\Module\Order\DTO\InvoiceShowDTO;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Services\Pipelines\Problems\Invoice\CourierReturnWithoutWaitListPipeline;
use App\Module\Order\Services\Pipelines\Problems\Invoice\InvoiceDeliveringPipeline;
use App\Module\Order\Services\Pipelines\Problems\Invoice\InvoiceTimerPipeline;
use App\Module\Order\Services\Pipelines\Problems\Invoice\ReturnedDeliveryManyTimesPipeline;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;

final readonly class InvoiceService implements InvoiceServiceContract
{
    public function __construct(
        private InvoiceQuery $invoiceQuery
    ) {
    }

    public function getInvoices(InvoiceShowDTO $DTO): InvoicesDTO
    {
        $invoices = $this->invoiceQuery->getInvoices($DTO);

        $dto                     = new InvoicesDTO();
        $dto->date               = $DTO->date;
        $dto->invoicesCount      = $invoices->count();
        $dto->dispatcherSectorId = $DTO->dispatcherSectorId;
        $dto->setInvoices($invoices);
        $dto->setStopsCount($invoices);

        return $dto;
    }

    public function getInvoiceProblemsById(int $invoiceId): Invoice
    {
        $invoice = $this->invoiceQuery
            ->getById($invoiceId, ['*'], ['statuses:id,invoice_id,code,created_at']);

        if (!$invoice) {
            throw new \DomainException('Накладная не найдена');
        }

        $invoice->problems = $invoice->getProblems();

        return $invoice;
    }

    public function getById(int $invoiceId, array $columns = ['*'], array $relations = []): ?Invoice
    {
        return $this->invoiceQuery->getById($invoiceId, $columns, $relations);
    }

    public function getProblems(Invoice $invoice): Collection
    {
        /** @var InvoiceProblemDTO $result */
        $result = app(Pipeline::class)
            ->send(new InvoiceProblemDTO($invoice))
            ->through([
                InvoiceTimerPipeline::class,
                InvoiceDeliveringPipeline::class,
                CourierReturnWithoutWaitListPipeline::class,
                ReturnedDeliveryManyTimesPipeline::class,
            ])
            ->thenReturn();

        return $result->errors;
    }
}
