<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Helpers\DateHelper;
use App\Module\DispatcherSector\Contracts\Queries\WaveQuery;
use App\Module\DispatcherSector\Models\Wave;
use App\Module\Order\Commands\SetInvoiceWaveCommand;
use App\Module\Order\Contracts\Queries\InvoiceQuery;
use App\Module\Order\Contracts\Repositories\UpdateInvoiceRepository;
use App\Module\Order\Models\Invoice;
use App\Module\Status\Contracts\Queries\OrderStatusQuery;
use App\Module\Status\Models\OrderStatus;
use Illuminate\Support\Facades\Log;

final class SetInvoiceWaveHandler
{
    private ?Wave $wave;
    private ?OrderStatus $status;
    private ?string $deliveryDate;

    public function __construct(
        private readonly InvoiceQuery $invoiceQuery,
        private readonly WaveQuery $waveQuery,
        private readonly OrderStatusQuery $orderStatusQuery,
        private readonly UpdateInvoiceRepository $updateInvoiceRepository
    ) {
        $this->status       = null;
        $this->deliveryDate = DateHelper::getDate(now());
    }

    public function handle(SetInvoiceWaveCommand $command): void
    {
        $invoice = $this->invoiceQuery->getById($command->invoiceId);

        if (!$invoice || !($dispatcherSectorId = $invoice->receiver?->dispatcher_sector_id)) {
            return;
        }

        $this->getWave($invoice, $dispatcherSectorId, $command->waveId);

        Log::info('Присвоение волны к накладной', [
            'invoiceId'    => $invoice->id,
            'code'         => $this->status?->code,
            'waveId'       => $this->wave?->id,
            'deliveryDate' => $this->deliveryDate,
        ]);

        $invoice->wave_id       = $this->wave?->id;
        $invoice->delivery_date = $this->deliveryDate;

        $this->updateInvoiceRepository->update($invoice);
    }

    private function getWave(Invoice $invoice, int $dispatcherSectorId, ?int $waveId): void
    {
        if ($waveId) {
            $this->wave = $this->waveQuery->getById($waveId);
            return;
        }

        $this->status = $this->orderStatusQuery->getStatusForWaveByInvoiceId($invoice->id);

        if (!$this->status) {
            return;
        }

        $data = $this->waveQuery->getByDispatcherSectorIdAndTime(
            $dispatcherSectorId,
            $this->status->created_at->addHours(Invoice::RESERVE_HOURS_TO_PROCESSING)
        );

        $this->wave         = $data['wave'];
        $this->deliveryDate = DateHelper::getDate($data['time']);
    }
}
