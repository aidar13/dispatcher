<?php

declare(strict_types=1);

namespace App\Module\Take\Requests;

use App\Module\Take\DTO\ChangeTakeDateDTO;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema (
 *     required={"orderId","newDate"},
 *     @OA\Property(property="orderId", type="int", description="Номер заказа", example="1"),
 *     @OA\Property(property="newDate", type="string", format="date", pattern="YYYY-MM-DD", example="2021-01-01"),
 *     @OA\Property(property="periodId", type="int", description="Период айди времени забора", example="1"),
 * )
 */
class ChangeTakeDateByOrderIdRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'orderId'  => ['required', 'int', 'exists:orders,id'],
            'newDate'  => ['required', 'string', 'date_format:Y-m-d'],
            'periodId' => ['required', 'int', 'exists:order_periods,id'],
            'userId'   => ['nullable', 'int']
        ];
    }

    public function getDTO(): ChangeTakeDateDTO
    {
        return ChangeTakeDateDTO::fromRequest($this);
    }
}
