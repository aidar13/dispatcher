<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Queries;

use App\Module\DispatcherSector\Models\Wave;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

interface WaveQuery
{
    public function getById(int $id): Wave;

    public function getByDispatcherSectorIdAndTime(int $dispatcherSectorId, Carbon $time): ?Collection;

    public function getAllByDispatcherSectorId(int $dispatcherSectorId): EloquentCollection;
}
