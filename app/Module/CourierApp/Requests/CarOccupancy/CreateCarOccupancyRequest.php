<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Requests\CarOccupancy;

use App\Module\CourierApp\DTO\CarOccupancy\DeliveryCarOccupancyDTO;
use App\Module\CourierApp\DTO\CarOccupancy\OrderTakeCarOccupancyDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema (
 *     required={"carOccupancyTypeId, clientId"},
 *     @OA\Property(property="carOccupancyTypeId", type="int", description="Номер типа заполнености машины", example="1"),
 *     @OA\Property(property="clientId", type="int", description="ID заказа|накладной", example="1"),
 * )
 */
final class CreateCarOccupancyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'carOccupancyTypeId' => ['required', 'integer', 'exists:car_occupancy_types,id'],
            'clientId'           => ['required', 'integer'],
        ];
    }

    public function getOrderTakeDTO(): OrderTakeCarOccupancyDTO
    {
        return OrderTakeCarOccupancyDTO::fromRequest($this);
    }

    public function getDeliveryDTO(): DeliveryCarOccupancyDTO
    {
        return DeliveryCarOccupancyDTO::fromRequest($this);
    }
}
