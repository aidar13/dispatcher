<?php

declare(strict_types=1);

namespace App\Module\Monitoring\DTO;

use App\Module\Delivery\Models\Delivery;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

final class MonitoringDeliveryDTO
{
    public Collection $total;
    public Collection $cancelled;
    public Collection $remained;
    public Collection $completed;

    public static function fromCollection(Collection $deliveries): MonitoringDeliveryDTO
    {
        $self            = new self();
        $self->total     = $self->reformData($deliveries);
        $self->cancelled = $self->reformData(
            $deliveries->filter(fn (Delivery $delivery) => $delivery->isReturned())
        );
        $self->remained  = $self->reformData(
            $deliveries->filter(fn (Delivery $delivery) => $delivery->isRemained())
        );
        $self->completed = $self->reformData(
            $deliveries->filter(fn (Delivery $delivery) => $delivery->isDelivered())
        );

        return $self;
    }

    private function reformData(Collection $deliveries): Collection
    {
        return $deliveries
            ->groupBy(['customer.sector.name'])
            ->map(function (Collection $items) {
                return [
                    'sectorId'   => Arr::get($items, '0.customer.sector_id'),
                    'sectorName' => Arr::get($items, '0.customer.sector.name'),
                    'count'      => $items->count()
                ];
            })
            ->values();
    }
}
