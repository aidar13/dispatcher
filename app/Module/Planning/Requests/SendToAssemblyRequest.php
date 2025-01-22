<?php

declare(strict_types=1);

namespace App\Module\Planning\Requests;

use App\Module\Planning\DTO\SendToAssemblyDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     @OA\Property(property="waveId", type="integer", example=1),
 *     @OA\Property(property="date", type="string", example="2023-08-11"),
 *     @OA\Property(property="sectorIds", @OA\Schema(type="array", @OA\Items(type="integer", example=1))),
 *     @OA\Property(property="containerIds", @OA\Schema(type="array", @OA\Items(type="integer", example=1))),
 * )
 */
final class SendToAssemblyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'waveId'         => ['required_without:containerIds', 'integer'],
            'date'           => ['required_without:containerIds', 'date_format:Y-m-d'],
            'sectorIds'      => ['nullable', 'array'],
            'sectorIds.*'    => ['required', 'integer'],
            'containerIds'   => ['required_without:waveId', 'array'],
            'containerIds.*' => ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'date.required_without'         => 'Поле date обязательно, если containerIds отсутствует.',
            'waveId.required_without'       => 'Поле waveId обязательно, если containerIds отсутствует.',
            'containerIds.required_without' => 'Поле containerIds обязательно, если waveId отсутствует.',
        ];
    }

    public function getDTO(): SendToAssemblyDTO
    {
        return SendToAssemblyDTO::fromRequest($this);
    }
}
