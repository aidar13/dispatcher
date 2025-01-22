<?php

declare(strict_types=1);

namespace App\Module\Order\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"date"},
 *
 *     @OA\Property(property="date", type="string", example="2023-08-11"),
 * )
 */
final class UpdateInvoiceDeliveryDateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['nullable', 'date_format:Y-m-d'],
        ];
    }
}
