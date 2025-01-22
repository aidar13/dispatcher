<?php

declare(strict_types=1);

namespace App\Module\Courier\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"courierId", "routingEnabled"},
 *
 *     @OA\Property(property="courierId", type="integer", example="1", description="айди курьера"),
 *     @OA\Property(property="routingEnabled", type="integer", example="false", description="выкл/вкл"),
 * )
 */
final class UpdateCourierRoutingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'courierId'      => ['required', 'int'],
            'routingEnabled' => ['required', 'bool'],
        ];
    }
}
