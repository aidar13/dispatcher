<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Requests\CourierState;

use App\Module\CourierApp\DTO\CourierState\CreateDeliveryCourierStateDTO;
use App\Module\CourierApp\DTO\CourierState\CreateOrderTakeCourierStateDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema (
 *     @OA\Property(property="clientId", type="int", example="1", description="ID забора/доставки"),
 *     @OA\Property(property="latitude", type="string", example="43.234", description="широта"),
 *     @OA\Property(property="longitude", type="string", example="74.123", description="долгота"),
 * )
 */
final class CreateCourierStateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'clientId'  => ['integer', 'required'],
            'latitude'  => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ];
    }

    public function getOrderTakeDTO(): CreateOrderTakeCourierStateDTO
    {
        return CreateOrderTakeCourierStateDTO::fromRequest($this);
    }

    public function getDeliveryDTO(): CreateDeliveryCourierStateDTO
    {
        return CreateDeliveryCourierStateDTO::fromRequest($this);
    }
}
