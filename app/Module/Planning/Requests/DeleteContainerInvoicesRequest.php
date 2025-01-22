<?php

declare(strict_types=1);

namespace App\Module\Planning\Requests;

use App\Module\Planning\DTO\DeleteContainerInvoicesDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"containerId", "invoiceIds"},
 *
 *     @OA\Property(property="containerId", type="integer", example=1, description="ID контейнера"),
 *     @OA\Property(property="invoiceIds", type="array", @OA\Items(example=1, description="ID накладной")),
 * )
 */
final class DeleteContainerInvoicesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'containerId'  => ['required', 'integer'],
            'invoiceIds'   => ['required', 'array'],
            'invoiceIds.*' => ['required', 'integer'],
        ];
    }

    public function getDTO(): DeleteContainerInvoicesDTO
    {
        return DeleteContainerInvoicesDTO::fromRequest($this);
    }
}
