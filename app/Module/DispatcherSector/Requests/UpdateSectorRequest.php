<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Requests;

use App\Module\DispatcherSector\DTO\UpdateSectorDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"name", "dispatcherSectorId", "coordinates", "color"},
 *
 *     @OA\Property(property="name", type="string", example="Сектор 1"),
 *     @OA\Property(property="dispatcherSectorId", type="integer", example=1),
 *     @OA\Property(property="coordinates", type="object", example="[[43.25900022611416, 76.9220136142039], [43.25900022611416, 76.92102558206726], [43.258843386009225, 76.91995163409266]]"),
 *     @OA\Property(property="color", type="string", example="#68CCCA"),
 * )
 */

final class UpdateSectorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'               => ['required', 'string'],
            'dispatcherSectorId' => ['required', 'integer', 'exists:dispatcher_sectors,id'],
            'coordinates'        => ['required', 'array'],
            'color'              => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'dispatcherSectorId.exists' => 'Выбран неверный dispatcherSectorId.',
        ];
    }

    public function getDTO(): UpdateSectorDTO
    {
        return UpdateSectorDTO::fromRequest($this);
    }
}
