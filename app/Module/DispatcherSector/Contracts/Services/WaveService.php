<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Services;

use App\Module\DispatcherSector\DTO\DispatcherWaveDTO;
use App\Module\DispatcherSector\DTO\WaveShowDTO;
use App\Module\DispatcherSector\Models\Wave;
use Illuminate\Support\Collection;

interface WaveService
{
    public function getAll(WaveShowDTO $DTO): Collection;

    public function getById(int $id): Wave;

    public function getByIdWithFilter(int $id, WaveShowDTO $DTO): DispatcherWaveDTO;
}
