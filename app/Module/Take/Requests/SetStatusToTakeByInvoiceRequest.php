<?php

declare(strict_types=1);

namespace App\Module\Take\Requests;

use App\Module\Take\DTO\SetStatusToTakeByInvoiceDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema (
 *     required={"invoiceId","statusId"},
 *     @OA\Property(property="invoiceId", type="int", description="ID накладной", example="1"),
 *     @OA\Property(property="statusId", type="int", description="ID статуса", example="1"),
 * )
 */
final class SetStatusToTakeByInvoiceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'invoiceId' => ['required', 'integer'],
            'statusId'  => ['required', 'integer'],
        ];
    }

    public function getDTO(): SetStatusToTakeByInvoiceDTO
    {
        return SetStatusToTakeByInvoiceDTO::fromRequest($this);
    }
}
