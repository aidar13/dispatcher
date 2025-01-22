<?php

declare(strict_types=1);

namespace App\Module\Planning\Requests;

use App\Module\Planning\DTO\GenerateContainerDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"dispatcherSectorId", "waveId", "date"},
 *
 *     @OA\Property(property="dispatcherSectorId", type="integer", example=1),
 *     @OA\Property(property="waveId", type="integer", example=1),
 *     @OA\Property(property="date", type="string", example="2023-08-11"),
 *     @OA\Property(property="sectorIds", @OA\Schema(type="array", @OA\Items(type="integer", example=1))),
 *     @OA\Property(property="statusId", type="integer", example=1),
 * )
 */
final class GenerateContainerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'dispatcherSectorId' => ['required', 'integer'],
            'waveId'             => ['required', 'integer'],
            'date'               => ['required', 'date_format:Y-m-d'],
            'sectorIds'          => ['nullable', 'array'],
            'sectorIds.*'        => ['required', 'integer'],
            'statusId'           => ['nullable', 'integer', 'in:1,2'],
        ];
    }

    public function getDTO(): GenerateContainerDTO
    {
        return GenerateContainerDTO::fromRequest($this);
    }
}
