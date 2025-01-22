<?php

declare(strict_types=1);

namespace App\Module\Delivery\Requests;

use App\Module\Delivery\DTO\RouteSheetFrom1CDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 *  @OA\Schema(
 *     required={"routeSheetNumber", "courierId"},
 *
 *     @OA\Property(property="routeSheetNumber",type="string",description="Номер марш листа",example="00000323232"),
 *     @OA\Property(property="courierId",type="integer",description="ID курьера", example="1"),
 * )
 *
 */
final class RouteSheetFromOneCRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'routeSheetNumber' => ['required', 'string', 'unique:route_sheets,number'],
            'courierId'        => ['required', 'int', 'exists:couriers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'routeSheetNumber.required' => 'Номер марш листа обьязателен к заполнению',
            'routeSheetNumber.unique'   => 'Номер марш листа уже существует',
            'courierId.exist'           => 'Курьер не найден',
        ];
    }

    /**
     * @return RouteSheetFrom1CDTO
     */
    public function getDTO(): RouteSheetFrom1CDTO
    {
        return RouteSheetFrom1CDTO::fromRequest($this);
    }
}
