<?php

declare(strict_types=1);

namespace App\Module\Delivery\DTO;

use App\Helpers\NumberHelper;

final class CarPredictionDetailDTO
{
    public int $autoCount = 0;
    public int $stopsCount = 0;
    public float $weight = 0;
    public float $volumeWeight = 0;

    public function setData(CarPredictionDTO $DTO): void
    {
        $this->autoCount    += $DTO->carCount;
        $this->stopsCount   += $DTO->stopsCount;
        $this->weight       += NumberHelper::getRounded($DTO->weight);
        $this->volumeWeight += NumberHelper::getRounded($DTO->volumeWeight);
    }
}
