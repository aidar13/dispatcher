<?php

declare(strict_types=1);

namespace App\Module\Planning\Requests;

use App\Module\Planning\DTO\CreateContainerDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"sectorId", "waveId", "date", "cargoType"},
 *
 *     @OA\Property(property="waveId", type="integer", example=1, description="ID волны"),
 *     @OA\Property(property="sectorId", type="integer", example=1, description="ID сектора"),
 *     @OA\Property(property="date", type="string", example="2023-08-11", description="Дата контейнера"),
 *     @OA\Property(property="cargoType", type="integer", example=1, description="Тип (Легковой = 1, Грузовой = 2)")),
 *     @OA\Property(property="invoiceIds", @OA\Schema(type="array", @OA\Items(type="integer", example=1)))
 * )
 */
final class CreateContainerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'waveId'       => ['required', 'integer', 'exists:waves,id'],
            'sectorId'     => ['required', 'integer', 'exists:sectors,id'],
            'cargoType'    => ['required', 'integer', 'in:1,2'],
            'date'         => ['required', 'date_format:Y-m-d'],
            'invoiceIds'   => ['nullable', 'array'],
            'invoiceIds.*' => ['required', 'integer'],
        ];
    }

    public function getDTO(): CreateContainerDTO
    {
        return CreateContainerDTO::fromRequest($this);
    }
}
