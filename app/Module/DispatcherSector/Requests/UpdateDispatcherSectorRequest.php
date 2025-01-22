<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Requests;

use App\Module\DispatcherSector\DTO\UpdateDispatcherSectorDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"cityId", "name", "description", "coordinates"},
 *     @OA\Property(property="cityId", type="integer", example=1),
 *     @OA\Property(property="deliveryManagerId", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Название Диспетчер сектора"),
 *     @OA\Property(property="coordinates", type="object", example="[[43.25900022611416, 76.9220136142039], [43.25900022611416, 76.92102558206726], [43.258843386009225, 76.91995163409266]]"),
 *     @OA\Property(property="description", type="string", example="Описание"),
 *     @OA\Property(property="dispatcherIds", type="object", example="[1,2,3]"),
 * )
 */
final class UpdateDispatcherSectorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cityId'            => ['required', 'integer', 'exists:cities,id'],
            'name'              => ['required', 'string'],
            'description'       => ['required', 'string'],
            'coordinates'       => ['required', 'array'],
            'deliveryManagerId' => ['nullable', 'integer'],
            'dispatcherIds'     => ['nullable', 'array'],
            'dispatcherIds.*'   => ['integer']
        ];
    }

    public function messages(): array
    {
        return [
            'cityId.exists' => 'Выбран неверный город.',
        ];
    }

    public function getDTO(): UpdateDispatcherSectorDTO
    {
        return UpdateDispatcherSectorDTO::fromRequest($this);
    }
}
