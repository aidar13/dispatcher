<?php

declare(strict_types=1);

namespace App\Module\Order\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     @OA\Property(property="waveId", type="number", example="1", description="ID волны"),
 * )
 */
final class UpdateInvoiceWaveRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'waveId' => ['nullable', 'exists:waves,id'],
        ];
    }
}
