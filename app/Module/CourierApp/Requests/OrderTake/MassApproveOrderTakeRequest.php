<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Requests\OrderTake;

use App\Module\CourierApp\DTO\OrderTake\MassApproveOrderTakeDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema (
 *     required={"orderId", "invoices"},
 *     @OA\Property(property="orderId", type="integer", description="ID заказа", example="1"),
 *     @OA\Property(property="invoices", type="array",
 *         @OA\Items(
 *             @OA\Property(property="invoiceNumber", type="string", description="Номер накладной", example="SP0000001"),
 *             @OA\Property(property="places", type="integer", description="кол-во мест", example="1")
 *         )
 *     )
 * )
 */
final class MassApproveOrderTakeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'orderId'                  => ['required', 'integer'],
            'invoices'                 => ['required', 'array'],
            'invoices.*.invoiceNumber' => ['required', 'string'],
            'invoices.*.places'        => ['required', 'integer']
        ];
    }

    public function getDTO(): MassApproveOrderTakeDTO
    {
        return MassApproveOrderTakeDTO::fromRequest($this);
    }
}
