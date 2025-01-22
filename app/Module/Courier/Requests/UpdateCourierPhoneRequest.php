<?php

declare(strict_types=1);

namespace App\Module\Courier\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"phoneNumber"},
 *
 *     @OA\Property(property="phoneNumber", type="string", example="87776661122", description="номер телефон курьера"),
 * )
 */
final class UpdateCourierPhoneRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phoneNumber' => ['required', 'unique:couriers,phone_number'],
        ];
    }

    public function messages(): array
    {
        return [
            'phoneNumber.unique' => 'Курьер с таким номером телефона уже существует',
        ];
    }
}
