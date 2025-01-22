<?php

declare(strict_types=1);

namespace App\Module\Courier\Handlers;

use App\Module\Courier\Commands\CloseCourierDayCommand;
use App\Module\Courier\Commands\SaveCloseCourierDayCommand;
use App\Module\Courier\Contracts\Queries\CourierReportQuery;
use App\Module\Courier\DTO\CourierInvoiceDTO;
use App\Module\Courier\Services\Pipelines\InvoiceHasInvoiceNumberHandlingPipeline;
use App\Module\Courier\Services\Pipelines\InvoicesDeliveredHandlingPipeline;
use App\Module\Courier\Services\Pipelines\TakeCargoHandlingPipeline;
use Illuminate\Pipeline\Pipeline;

final class CloseCourierDayHandler
{
    public function __construct(
        private readonly CourierReportQuery $reportQuery
    ) {
    }

    public function handle(CloseCourierDayCommand $command): CourierInvoiceDTO
    {
        $invoices = $this->reportQuery->getCloseDayReportCourierTakesAndDeliveries($command->courierId, $command->date);

        /** @var CourierInvoiceDTO $result */
        $result = app(Pipeline::class)
            ->send(new CourierInvoiceDTO($invoices))
            ->through([
                TakeCargoHandlingPipeline::class,
                InvoicesDeliveredHandlingPipeline::class,
                InvoiceHasInvoiceNumberHandlingPipeline::class,
            ])
            ->thenReturn();

        if ($result->errors->isEmpty()) {
            dispatch(new SaveCloseCourierDayCommand($command->courierId, $command->userId, $command->date));
        }

        return $result;
    }
}
