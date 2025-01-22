<?php

declare(strict_types=1);

namespace App\Module\Courier\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     @OA\Property(property="date",type="string", example="2023-08-11"),
 * )
 */
final class CourierReportShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date'  => ['required', 'string', 'date_format:Y-m-d'],
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'Дата обязательно к заполнению. (Y-m-d)',
        ];
    }
}
