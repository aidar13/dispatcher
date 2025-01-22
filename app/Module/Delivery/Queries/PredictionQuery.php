<?php

declare(strict_types=1);

namespace App\Module\Delivery\Queries;

use App\Helpers\DateHelper;
use App\Module\Delivery\Contracts\Queries\PredictionQuery as PredictionQueryContract;
use App\Module\Delivery\DTO\PredictionDTO;
use App\Module\Order\Models\Invoice;
use App\Module\Planning\Models\ContainerStatus;
use App\Module\Status\Models\RefStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class PredictionQuery implements PredictionQueryContract
{
    /**
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     */
    public function getReport(PredictionDTO $DTO): Collection
    {
        return Invoice::query()
            ->with([
                'cargo:invoice_id,weight,volume_weight',
                'receiver:id,sector_id,latitude,longitude,dispatcher_sector_id',
                'receiver.sector:id,name',
            ])
            ->whereDoesntHave('container', function (Builder $query) use ($DTO) {
                $query
                    ->where('containers.date', '!=', $DTO->date)
                    ->where('containers.status_id', '!=', ContainerStatus::ID_CREATED)
                    ->orWhere('containers.date', '=', $DTO->date);
            })
            ->whereRelation('wave', 'dispatcher_sector_id', $DTO->dispatcherSectorId)
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
            ->orderBy('wave_id')
            ->get();
    }
}
