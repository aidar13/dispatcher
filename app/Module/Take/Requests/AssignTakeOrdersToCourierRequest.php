<?php

declare(strict_types=1);

namespace App\Module\Take\Requests;

use App\Module\Take\DTO\AssignOrderTakeDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema (
 *     required={"orderIds", "courierId"},
 *     @OA\Property(property="orderIds", type="array", description="Айди заказов(мультиселект)", @OA\Items(type="enum", enum={1,2,3,4,5,6,7,8,9}), example={1,2}),
 *     @OA\Property(property="courierId", description="ID курьера", type="integer", example="1"),
 * )
 */

final class AssignTakeOrdersToCourierRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'orderIds'  => ['required', 'array'],
            'courierId' => ['required', 'exists:couriers,id']
        ];
    }

    public function getDTO(): AssignOrderTakeDTO
    {
        return AssignOrderTakeDTO::fromRequest($this);
    }
}
