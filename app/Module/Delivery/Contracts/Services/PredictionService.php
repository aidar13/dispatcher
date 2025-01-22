<?php

declare(strict_types=1);

namespace App\Module\Delivery\Contracts\Services;

use App\Module\Delivery\DTO\CarPredictionReportDTO;
use App\Module\Delivery\DTO\PredictionDTO;
use App\Module\Delivery\DTO\PredictionReportDTO;

interface PredictionService
{
    public function getReport(PredictionDTO $DTO): PredictionReportDTO;

    public function getCarsReport(PredictionDTO $DTO): CarPredictionReportDTO;
}
