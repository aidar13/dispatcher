<?php

declare(strict_types=1);

namespace App\Module\Planning\Requests;

use App\Module\Planning\DTO\ChangeContainerStatusDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"containerId", "containerStatusId"},
 *     @OA\Property(property="containerId", type="integer", example=1),
 *     @OA\Property(property="containerStatusId", type="integer", example=1),
 *     @OA\Property(
 *         property="invoices",
 *         type="array",
 *         description="Наклданые",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="invoiceNumber", type="string", example="SP000123"),
 *             @OA\Property(property="invoiceStatusId", type="integer", example=1),
 *             @OA\Property(property="placesQuantity", type="integer", example=1)
 *         )
 *     )
 * )
 */
final class ChangeContainerStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'containerId'                => ['required', 'exists:containers,id'],
            'containerStatusId'          => ['required'],
            'invoices'                   => ['array'],
            'invoices.*.invoiceNumber'   => ['required', 'exists:invoices,invoice_number'],
            'invoices.*.invoiceStatusId' => ['required'],
            'invoices.*.placesQuantity'  => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'containerId.required'                => 'Заполните ID контейнера',
            'containerId.exists'                  => 'Данного контейнера не существует',
            'containerStatusId.required'          => 'Заполните статус контейнера',
            'invoices.*.invoiceNumber.exists'     => 'Данная накладная не существует',
            'invoices.*.invoiceStatusId.required' => 'Заполните статус накладной',
            'invoices.*.placesQuantity.required'  => 'Заполните количество мест'
        ];
    }

    public function getDTO(): ChangeContainerStatusDTO
    {
        return ChangeContainerStatusDTO::fromRequest($this);
    }
}
