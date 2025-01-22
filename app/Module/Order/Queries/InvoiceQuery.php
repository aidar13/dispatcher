<?php

declare(strict_types=1);

namespace App\Module\Order\Queries;

use App\Helpers\DateHelper;
use App\Module\DispatcherSector\DTO\WaveShowDTO;
use App\Module\Order\Contracts\Queries\InvoiceQuery as InvoiceQueryContract;
use App\Module\Order\DTO\InvoiceShowDTO;
use App\Module\Order\Models\Invoice;
use App\Module\Planning\Models\ContainerStatus;
use App\Module\Status\Models\RefStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class InvoiceQuery implements InvoiceQueryContract
{
    public function getById(int $id, array $columns = ['*'], array $relations = []): ?Invoice
    {
        /** @var Invoice|null */
        return Invoice::query()
            ->select($columns)
            ->with($relations)
            ->find($id);
    }

    public function getWaveInvoices(WaveShowDTO $DTO): Collection
    {
        return Invoice::query()
            ->select('invoices.*')
            ->whereNull('type')
            ->whereDoesntHave('container', function (Builder $query) use ($DTO) {
                $query
                    ->where('containers.date', '!=', $DTO->date)
                    ->where('containers.status_id', '!=', ContainerStatus::ID_CREATED)
                    ->orWhere('containers.date', '=', $DTO->date);
            })
            ->with([
                'cargo:invoice_id,weight,places,volume_weight',
                'receiver:id,longitude,latitude,sector_id',
                'receiver.sector:id,name',
                'additionalServiceValues:id,client_id,client_type,type_id'
            ])
            ->join('receivers', 'receivers.id', '=', 'invoices.receiver_id')
            ->where('wave_id', $DTO->waveId)
            ->whereRelation('receiver', 'dispatcher_sector_id', $DTO->dispatcherSectorId)
            ->where(function (Builder $query) use ($DTO) {
                $query
                    ->where('status_id', RefStatus::ID_CARGO_IN_TRANSIT)
                    ->where('delivery_date', '<=', DateHelper::getDate($DTO->date))
                    ->whereHas('statuses', function (Builder $query) use ($DTO) {
                        $query->where('code', RefStatus::CODE_APPROXIMATE_DELIVERY_TO_CITY);
                    })
                    ->orWhereIn('status_id', [RefStatus::ID_CARGO_AWAIT_SHIPMENT, RefStatus::ID_CARGO_ARRIVED_CITY])
                    ->orWhere('status_id', RefStatus::ID_DELIVERY_IN_PROGRESS)
                    ->whereHas('returnDeliveries');
            })
            ->when($DTO->sectorId, function (Builder $query) use ($DTO) {
                $query->whereRelation('receiver', 'sector_id', '=', $DTO->sectorId);
            })
            ->when($DTO->additionalServices, function (Builder $query) use ($DTO) {
                $query->whereHas('additionalServiceValues', function (Builder $query) use ($DTO) {
                    $query->whereIn('type_id', $DTO->additionalServices);
                });
            })
            ->when($DTO->statusId === Invoice::STATUS_FACT, function (Builder $query) {
                $query->whereIn('status_id', [RefStatus::ID_CARGO_AWAIT_SHIPMENT, RefStatus::ID_CARGO_ARRIVED_CITY, RefStatus::ID_DELIVERY_IN_PROGRESS]);
            })
            ->when($DTO->statusId === Invoice::STATUS_DELIVERING, function (Builder $query) {
                $query->where('status_id', RefStatus::ID_CARGO_IN_TRANSIT);
            })
            ->orderByRaw('receivers.latitude - receivers.longitude')
            ->get();
    }

    public function getInvoices(InvoiceShowDTO $DTO): Collection
    {
        return Invoice::query()
            ->with(['cargo', 'receiver.sector:id,name', 'waitListStatus:id,name'])
            ->whereDoesntHave('container', function (Builder $query) use ($DTO) {
                $query
                    ->where('containers.date', '!=', $DTO->date)
                    ->where('containers.status_id', '!=', ContainerStatus::ID_CREATED)
                    ->orWhere('containers.date', '=', $DTO->date);
            })
            ->where(function ($query) use ($DTO) {
                $query
                    ->where('status_id', RefStatus::ID_CARGO_IN_TRANSIT)
                    ->where('delivery_date', '<=', $DTO->date)
                    ->whereHas('statuses', function ($query) {
                        $query->where('code', RefStatus::CODE_APPROXIMATE_DELIVERY_TO_CITY);
                    })
                    ->orWhereIn('status_id', [RefStatus::ID_CARGO_AWAIT_SHIPMENT, RefStatus::ID_CARGO_ARRIVED_CITY])
                    ->orWhere('status_id', RefStatus::ID_DELIVERY_IN_PROGRESS)
                    ->whereHas('returnDeliveries');
            })
            ->when($DTO->invoiceNumber, fn(Builder $q) => $q->where('invoice_number', 'like', '%' . $DTO->invoiceNumber . '%'))
            ->when($DTO->waitListStatus, fn(Builder $query) => $query->where('wait_list_id', $DTO->waitListStatus))
            ->when($DTO->dispatcherSectorId, fn(Builder $query) => $query->whereRelation('receiver', 'dispatcher_sector_id', $DTO->dispatcherSectorId))
            ->get();
    }

    public function getByInvoiceNumber(string $invoiceNumber): Invoice
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::query()
            ->where('invoice_number', $invoiceNumber)
            ->first();

        return $invoice;
    }
}
