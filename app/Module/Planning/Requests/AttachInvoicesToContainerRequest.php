<?php

declare(strict_types=1);

namespace App\Module\Planning\Requests;

use App\Module\Planning\DTO\AttachInvoicesToContainerDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $containerId
 *
 * @OA\Schema(
 *     required={"invoiceIds"},
 *
 *     @OA\Property(property="invoiceIds", type="array", @OA\Items(example=1, description="ID накладной")),
 * )
 */
final class AttachInvoicesToContainerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'invoiceIds'    => ['required', 'array'],
            'invoiceIds.*'  => ['required', 'integer', 'exists:invoices,id'],
        ];
    }

    public function getDTO(): AttachInvoicesToContainerDTO
    {
        return AttachInvoicesToContainerDTO::fromRequest($this);
    }
}
