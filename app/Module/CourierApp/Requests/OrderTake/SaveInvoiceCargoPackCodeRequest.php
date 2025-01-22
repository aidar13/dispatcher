<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Requests\OrderTake;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"packCode"},
 *     @OA\Property(property="packCode",type="string",description="Штрих код")
 * )
 */
final class SaveInvoiceCargoPackCodeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'packCode' => ['required', 'string']
        ];
    }
}
