<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Requests;

use App\Module\DispatcherSector\DTO\SectorShowDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     @OA\Property(property="limit",type="integer", example=20),
 *     @OA\Property(property="page",type="integer", example=1),
 *     @OA\Property(property="name",type="string", example="Алматы"),
 *     @OA\Property(property="cityId",type="integer", example=1),
 *     @OA\Property(property="dispatcherSectorIds", @OA\Schema(type="array", @OA\Items(type="integer", example=1)))
 * )
 */

final class SectorShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'limit'               => ['nullable', 'integer'],
            'page'                => ['nullable', 'integer'],
            'name'                => ['nullable', 'string'],
            'cityId'              => ['nullable', 'integer', 'exists:cities,id'],
            'dispatcherSectorIds' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'cityId.exists'       => 'Выбран неверный город.',
        ];
    }

    public function getDTO(): SectorShowDTO
    {
        return SectorShowDTO::fromRequest($this);
    }
}
