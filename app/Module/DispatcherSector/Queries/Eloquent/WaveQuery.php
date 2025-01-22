<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Queries\Eloquent;

use App\Module\DispatcherSector\Contracts\Queries\WaveQuery as WaveQueryContract;
use App\Module\DispatcherSector\Models\Wave;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final class WaveQuery implements WaveQueryContract
{
    public function getById(int $id): Wave
    {
        return Wave::where('id', $id)->firstOrFail();
    }

    public function getByDispatcherSectorIdAndTime(int $dispatcherSectorId, Carbon $time): Collection
    {
        $wave = Wave::query()
            ->where('dispatcher_sector_id', $dispatcherSectorId)
            ->whereTime('from_time', '>=', $time->format('H:i'))
            ->orderBy('from_time')
            ->firstOr(function () use ($dispatcherSectorId, $time) {
                return Wave::query()
                    ->where('dispatcher_sector_id', $dispatcherSectorId)
                    ->whereTime('from_time', '>=', $time->addDay()->startOfDay()->format('H:i'))
                    ->orderBy('from_time')
                    ->first();
            });

        return collect([
            'wave' => $wave,
            'time' => $time
        ]);
    }

    /**
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     */
    public function getAllByDispatcherSectorId(int $dispatcherSectorId): EloquentCollection
    {
        return Wave::query()
            ->where('dispatcher_sector_id', $dispatcherSectorId)
            ->get();
    }
}
