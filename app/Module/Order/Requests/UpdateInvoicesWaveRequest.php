<?php

declare(strict_types=1);

namespace App\Module\Order\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"waveId", "invoiceIds"},
 *
 *     @OA\Property(property="waveId", type="number", example="1", description="ID волны"),
 *     @OA\Property(property="invoiceIds", @OA\Schema(type="array", @OA\Items(type="integer", example=1))),
 * )
 */
final class UpdateInvoicesWaveRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'waveId'       => ['required', 'exists:waves,id'],
            'invoiceIds'   => ['required', 'array'],
            'invoiceIds.*' => ['required', 'integer', 'exists:invoices,id'],
        ];
    }
}
