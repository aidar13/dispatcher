<?php

declare(strict_types=1);

namespace App\Module\Order\Requests;

use App\Module\Order\DTO\InvoiceShowDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"dispatcherSectorId", "date"},
 *
 *     @OA\Property(property="dispatcherSectorId", type="integer", example=1),
 *     @OA\Property(property="date", type="string", example="2023-08-11"),
 *     @OA\Property(property="invoiceNumber", type="string", example=1),
 * )
 */
final class InvoiceShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'dispatcherSectorId' => ['required', 'integer'],
            'waveId'             => ['nullable', 'integer'],
            'date'               => ['required', 'date_format:Y-m-d'],
            'invoiceNumber'      => ['nullable', 'string'],
        ];
    }

    public function getDTO(): InvoiceShowDTO
    {
        return InvoiceShowDTO::fromRequest($this);
    }
}
