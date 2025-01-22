<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Handlers\Delivery;

use App\Module\Car\Contracts\Queries\CarOccupancyQuery;
use App\Module\Car\Contracts\Queries\CarOccupancyTypeQuery;
use App\Module\Car\Models\CarOccupancyType;
use App\Module\CourierApp\Commands\CarOccupancy\CreateCarOccupancyCommand;
use App\Module\CourierApp\Commands\Delivery\CalculateCarOccupancyCommand;
use App\Module\CourierApp\DTO\CarOccupancy\DeliveryCarOccupancyDTO;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Delivery\Models\Delivery;
use DomainException;
use Illuminate\Contracts\Bus\Dispatcher;

final class CalculateCarOccupancyHandler
{
    public function __construct(
        private readonly Dispatcher $dispatcher,
        private readonly CarOccupancyTypeQuery $carOccupancyTypeQuery,
        private readonly DeliveryQuery $deliveryQuery,
        private readonly CarOccupancyQuery $carOccupancyQuery,
    ) {
    }

    public function handle(CalculateCarOccupancyCommand $command): void
    {
        $delivery = $this->deliveryQuery->getById($command->deliveryId);

        if ($delivery->courier === null) {
            throw new DomainException('К этому заказу не привязан курьер.');
        }

        $this->dispatcher->dispatch(new CreateCarOccupancyCommand(
            $delivery->courier->user_id,
            $this->getDTO($delivery),
        ));
    }

    private function getDTO(Delivery $delivery): DeliveryCarOccupancyDTO
    {
        $carOccupancyType = $this->getCarOccupancyType($delivery);

        $DTO = new DeliveryCarOccupancyDTO();
        $DTO->setCarOccupancyTypeId($carOccupancyType->id);
        $DTO->setClientId($delivery->invoice_id);
        $DTO->setClientType();
        $DTO->setTypeId();

        return $DTO;
    }

    private function getCarOccupancyType(Delivery $delivery): CarOccupancyType
    {
        $lastCarOccupancy = $this->carOccupancyQuery->getCurrent($delivery->courier->car_id, $delivery->courier->user_id);
        $newOccupancy     = $this->calculateNewOccupancy(
            $lastCarOccupancy?->carOccupancyType?->percent ?? 0,
            $delivery->courier->car->cubature,
            $delivery->invoice->cargo->cubature,
        );

        return $this->carOccupancyTypeQuery->getByPercent($newOccupancy);
    }

    private function calculateNewOccupancy(int $lastOccupancy, int $carCubature, int $invoiceCubature = 0): int
    {
        if ($carCubature <= 0) {
            return 0;
        }

        $occupancy    = (int)round($invoiceCubature * 100 / $carCubature);
        $newOccupancy = $lastOccupancy - $occupancy;

        return max($newOccupancy, 0);
    }
}
