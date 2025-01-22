<?php

declare(strict_types=1);

namespace App\Module\Planning\Queries;

use App\Module\Planning\Contracts\Queries\PlanningQuery as PlanningQueryContract;
use App\Module\Planning\DTO\PlanningShowDTO;
use App\Module\Delivery\Models\Prediction;
use App\Module\DispatcherSector\Models\Sector;
use App\Module\Order\Models\Invoice;
use App\Module\Planning\DTO\SectorInvoiceDTO;
use App\Module\Planning\Models\ContainerStatus;
use App\Module\Status\Models\RefStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class PlanningQuery implements PlanningQueryContract
{
    public function getSectors(PlanningShowDTO $DTO): Collection
    {
        /** @var Collection */
        return Sector::query()
            ->with([
                'invoices' => function ($query) use ($DTO) {
                    $query
                        ->with([
                            'cargo', 'receiver.sector:id,name', 'waitListStatus:id,name,code',
                            'statuses:id,invoice_id,code,created_at'
                        ])
                        ->whereDoesntHave('container', function (Builder $query) use ($DTO) {
                            $query
                                ->where('containers.date', '!=', $DTO->date)
                                ->where('containers.status_id', '!=', ContainerStatus::ID_CREATED)
                                ->orWhere('containers.date', '=', $DTO->date);
                        })
                        ->where(function (Builder $query) {
                            $query
                                ->whereNot('wait_list_id', RefStatus::ID_ON_HOLD)
                                ->orWhereNull('wait_list_id');
                        })
                        ->where('wave_id', $DTO->waveId)
                        ->where(function ($query) use ($DTO) {
                            $query
                                ->where('status_id', RefStatus::ID_CARGO_IN_TRANSIT)
                                ->where('delivery_date', '<=', $DTO->date)
                                ->whereHas('statuses', function ($query) {
                                    $query->where('code', RefStatus::CODE_APPROXIMATE_DELIVERY_TO_CITY);
                                })
                                ->orWhereIn('status_id', [RefStatus::ID_CARGO_AWAIT_SHIPMENT, RefStatus::ID_CARGO_ARRIVED_CITY, RefStatus::ID_COURIER_RETURN_DELIVERY])
                                ->orWhere('status_id', RefStatus::ID_DELIVERY_IN_PROGRESS)
                                ->whereHas('returnDeliveries');
                        })
                        ->when($DTO->invoiceNumber, fn(Builder $q) => $q->where('invoice_number', 'like', '%' . $DTO->invoiceNumber . '%'))
                        ->when($DTO->statusId === Prediction::STATUS_ID_FACT, function (Builder $query) {
                            $query->whereIn('status_id', [RefStatus::ID_CARGO_AWAIT_SHIPMENT, RefStatus::ID_CARGO_ARRIVED_CITY, RefStatus::ID_DELIVERY_IN_PROGRESS, RefStatus::ID_COURIER_RETURN_DELIVERY]);
                        })
                        ->when($DTO->statusId === Prediction::STATUS_ID_DELIVERING, function (Builder $query) {
                            $query->where('status_id', RefStatus::ID_CARGO_IN_TRANSIT);
                        })
                        ->orderByRaw('receivers.latitude - receivers.longitude');
                },
                'containers' => function ($query) use ($DTO) {
                    $query
                        ->with([
                            'courier', 'invoices.waitListStatus:id,name', 'status', 'fastDeliveryOrder',
                            'invoices' => function ($query) {
                                $query
                                    ->with(['statuses:id,invoice_id,code,created_at', 'receiver', 'waitListStatus', 'cargo']);
                            }
                        ])
                        ->where('date', $DTO->date)
                        ->when($DTO->waveId, fn(Builder $query) => $query->where('wave_id', $DTO->waveId))
                        ->when($DTO->invoiceNumber, fn(Builder $q) => $q->whereRelation('invoices', 'invoice_number', 'like', '%' . $DTO->invoiceNumber . '%'))
                        ->orderBy('id');
                }
            ])
            ->where(function (Builder $query) use ($DTO) {
                $query
                    ->whereHas('invoices', function (Builder $query) use ($DTO) {
                        $query
                            ->where('status_id', RefStatus::ID_CARGO_IN_TRANSIT)
                            ->where('delivery_date', '<=', $DTO->date)
                            ->whereHas('statuses', function ($q) use ($DTO) {
                                $q->where('code', RefStatus::CODE_APPROXIMATE_DELIVERY_TO_CITY);
                            })
                            ->when($DTO->invoiceNumber, fn(Builder $q) => $q->where('invoice_number', 'like', '%' . $DTO->invoiceNumber . '%'))
                            ->orWhereIn('status_id', [RefStatus::ID_CARGO_AWAIT_SHIPMENT, RefStatus::ID_CARGO_ARRIVED_CITY])
                            ->orWhere('status_id', RefStatus::ID_DELIVERY_IN_PROGRESS)
                            ->whereHas('returnDeliveries');
                    })
                    ->orWhereHas('containers', function (Builder $query) use ($DTO) {
                        $query
                            ->where('date', $DTO->date)
                            ->when($DTO->waveId, fn(Builder $q) => $q->where('wave_id', $DTO->waveId))
                            ->when($DTO->invoiceNumber, fn(Builder $q) => $q->whereRelation('invoices', 'invoice_number', 'like', '%' . $DTO->invoiceNumber . '%'));
                    });
            })
            ->where('dispatcher_sector_id', $DTO->dispatcherSectorId)
            ->when($DTO->sectorIds, fn($query) => $query->whereIn('id', $DTO->sectorIds))
            ->get();
    }

    public function getSectorInvoices(SectorInvoiceDTO $DTO): Collection
    {
        /** @var Collection */
        return Invoice::query()
            ->select(['invoices.id', 'invoices.invoice_number', 'invoices.cargo_type'])
            ->whereDoesntHave('container', function (Builder $query) use ($DTO) {
                $query
                    ->where('containers.date', '!=', $DTO->date)
                    ->where('containers.status_id', '!=', ContainerStatus::ID_CREATED)
                    ->orWhere('containers.date', '=', $DTO->date);
            })
            ->whereRelation('receiver', 'sector_id', $DTO->sectorId)
            ->where('wave_id', $DTO->waveId)
            ->where(function (Builder $query) {
                $query
                    ->whereNot('wait_list_id', RefStatus::ID_ON_HOLD)
                    ->orWhereNull('wait_list_id');
            })
            ->where(function ($query) use ($DTO) {
                $query
                    ->where('status_id', RefStatus::ID_CARGO_IN_TRANSIT)
                    ->where('delivery_date', '<=', $DTO->date)
                    ->whereHas('statuses', function (Builder $query) use ($DTO) {
                        $query->where('code', RefStatus::CODE_APPROXIMATE_DELIVERY_TO_CITY);
                    })
                    ->orWhereIn('status_id', [RefStatus::ID_CARGO_AWAIT_SHIPMENT, RefStatus::ID_CARGO_ARRIVED_CITY, RefStatus::ID_COURIER_RETURN_DELIVERY])
                    ->orWhere('status_id', RefStatus::ID_DELIVERY_IN_PROGRESS)
                    ->whereHas('returnDeliveries');
            })
            ->when($DTO->statusId === Prediction::STATUS_ID_FACT, function (Builder $query) {
                $query->whereIn('status_id', [RefStatus::ID_CARGO_AWAIT_SHIPMENT, RefStatus::ID_CARGO_ARRIVED_CITY, RefStatus::ID_DELIVERY_IN_PROGRESS, RefStatus::ID_COURIER_RETURN_DELIVERY]);
            })
            ->when($DTO->statusId === Prediction::STATUS_ID_DELIVERING, function (Builder $query) {
                $query->where('status_id', RefStatus::ID_CARGO_IN_TRANSIT);
            })
            ->filterByLocationAndDistance($DTO->latitude, $DTO->longitude)
            ->get();
    }

    public function getInvoicesForRoutingByDispatcherSectorId(int $id, string $date): Collection
    {
        /** @var Collection */
        return Invoice::query()
            ->select(['invoices.id', 'invoices.invoice_number', 'invoices.cargo_type'])
            ->whereRelation('receiver', 'dispatcher_sector_id', $id)
            ->whereDoesntHave('container', function (Builder $query) use ($date) {
                $query
                    ->where('containers.date', '!=', $date)
                    ->where('containers.status_id', '!=', ContainerStatus::ID_CREATED)
                    ->orWhere('containers.date', '=', $date);
            })
            ->where(function (Builder $query) {
                $query
                    ->whereNot('wait_list_id', RefStatus::ID_ON_HOLD)
                    ->orWhereNull('wait_list_id');
            })
            ->where(function ($query) use ($date) {
                $query
                    ->where('status_id', RefStatus::ID_CARGO_IN_TRANSIT)
                    ->where('delivery_date', '<=', $date)
                    ->whereHas('statuses', function (Builder $query) {
                        $query->where('code', RefStatus::CODE_APPROXIMATE_DELIVERY_TO_CITY);
                    })
                    ->orWhereIn('status_id', [RefStatus::ID_CARGO_AWAIT_SHIPMENT, RefStatus::ID_CARGO_ARRIVED_CITY, RefStatus::ID_COURIER_RETURN_DELIVERY])
                    ->orWhere('status_id', RefStatus::ID_DELIVERY_IN_PROGRESS)
                    ->whereHas('returnDeliveries');
            })
            ->get();
    }
}
