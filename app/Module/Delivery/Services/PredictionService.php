<?php

declare(strict_types=1);

namespace App\Module\Delivery\Services;

use App\Helpers\DateHelper;
use App\Module\Delivery\Contracts\Queries\PredictionQuery;
use App\Module\Delivery\Contracts\Services\PredictionService as PredictionServiceContract;
use App\Module\Delivery\DTO\CarPredictionReportDTO;
use App\Module\Delivery\DTO\PredictionDTO;
use App\Module\Delivery\DTO\PredictionReportDTO;

final class PredictionService implements PredictionServiceContract
{
    public function __construct(private readonly PredictionQuery $predictionQuery)
    {
    }

    public function getReport(PredictionDTO $DTO): PredictionReportDTO
    {
        $invoices = $this->predictionQuery->getReport($DTO);

        $dto                     = new PredictionReportDTO();
        $dto->dispatcherSectorId = $DTO->dispatcherSectorId;
        $dto->date               = DateHelper::getDate($DTO->date);
        $dto->setIncoming($invoices);
        $dto->setFact($invoices);

        return $dto;
    }

    public function getCarsReport(PredictionDTO $DTO): CarPredictionReportDTO
    {
        $invoices = $this->predictionQuery->getReport($DTO);

        $dto                     = new CarPredictionReportDTO();
        $dto->dispatcherSectorId = $DTO->dispatcherSectorId;
        $dto->date               = DateHelper::getDate($DTO->date);
        $dto->setCars($invoices);

        return $dto;
    }
}
