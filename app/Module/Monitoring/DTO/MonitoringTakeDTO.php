<?php

declare(strict_types=1);

namespace App\Module\Monitoring\DTO;

use App\Module\Status\Models\StatusType;
use Illuminate\Database\Eloquent\Collection as CollectionEloquent;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

final class MonitoringTakeDTO
{
    public Collection $total;
    public Collection $cancelled;
    public Collection $remained;
    public Collection $completed;

    public static function fromCollection(CollectionEloquent $takes): self
    {
        $self            = new self();
        $self->total     = $self->reformData($takes);
        $self->cancelled = $self->reformData(
            $takes->where('status_id', StatusType::ID_TAKE_CANCELED)
        );
        $self->remained  = $self->reformData(
            $takes->whereNotIn('status_id', [StatusType::ID_CARGO_HANDLING, StatusType::ID_TAKE_CANCELED])
        );
        $self->completed = $self->reformData(
            $takes->where('status_id', StatusType::ID_CARGO_HANDLING)
        );

        return $self;
    }

    private function reformData(Collection $takes): Collection
    {
        return $takes
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
