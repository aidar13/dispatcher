<?php

declare(strict_types=1);

namespace App\Module\Monitoring\Contracts\Services;

use App\Module\Monitoring\DTO\CourierInfoShowDTO;
use App\Module\Monitoring\DTO\DeliveryInfoShowDTO;
use App\Module\Monitoring\DTO\MonitoringDeliveryDTO;
use App\Module\Monitoring\DTO\MonitoringTakeDTO;
use App\Module\Monitoring\DTO\TakeInfoShowDTO;
use Illuminate\Support\Collection;

interface MonitoringService
{
    public function getDeliverInfo(DeliveryInfoShowDTO $DTO): MonitoringDeliveryDTO;

    public function getTakeInfo(TakeInfoShowDTO $DTO): MonitoringTakeDTO;

    public function getCourierInfo(CourierInfoShowDTO $DTO): Collection;
}
