<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Requests;

use App\Module\DispatcherSector\DTO\WaveShowDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"dispatcherSectorId"},
 *
 *     @OA\Property(property="dispatcherSectorId", type="integer", example=1),
 *     @OA\Property(property="additionalServices", type="integer", example=1),
 *     @OA\Property(property="sectorId", type="integer", example=1),
 *     @OA\Property(property="statusId", type="integer", example=1),
 * )
 */
final class WaveShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'dispatcherSectorId' => ['nullable', 'integer'],
            'sectorId'           => ['nullable', 'integer'],
            'statusId'           => ['nullable', 'integer'],
            'additionalServices' => ['nullable', 'array'],
        ];
    }

    public function getDTO(): WaveShowDTO
    {
        return WaveShowDTO::fromRequest($this);
    }
}
