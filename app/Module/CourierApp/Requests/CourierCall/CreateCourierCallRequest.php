<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Requests\CourierCall;

use App\Module\CourierApp\DTO\CourierCall\CreateDeliveryCourierCallDTO;
use App\Module\CourierApp\DTO\CourierCall\CreateOrderTakeCourierCallDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema (
 *     required={"clientId","phone"},
 *     @OA\Property(property="clientId", type="int", example="1", description="Id забора или доставки"),
 *     @OA\Property(property="phone", type="string", example="+77777777777", description="номер телефона")
 * )
 */
final class CreateCourierCallRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'clientId'  => ['required', 'integer'],
            'phone'     => ['required', 'string']
        ];
    }

    public function getOrderTakeDTO(): CreateOrderTakeCourierCallDTO
    {
        return CreateOrderTakeCourierCallDTO::fromRequest($this);
    }

    public function getDeliveryDTO(): CreateDeliveryCourierCallDTO
    {
        return CreateDeliveryCourierCallDTO::fromRequest($this);
    }
}
