<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Requests;

use App\Module\DispatcherSector\DTO\WaveDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"title", "fromTime", "toTime", "dispatcherSectorId"},
 *
 *     @OA\Property(property="title", type="string", example="Волна 1"),
 *     @OA\Property(property="dispatcherSectorId", type="integer", example="1"),
 *     @OA\Property(property="fromTime", type="string", example=""),
 *     @OA\Property(property="toTime", type="string", example=""),
 * )
 */
final class WaveRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'              => ['required', 'string'],
            'dispatcherSectorId' => ['required', 'integer', 'exists:dispatcher_sectors,id'],
            'fromTime'           => ['required', 'date_format:H:i'],
            'toTime'             => ['required', 'date_format:H:i'],
        ];
    }

    public function messages(): array
    {
        return [
            'dispatcherSectorId.exists' => 'Выбран неверный dispatcherSectorId.',
        ];
    }

    public function getDTO(): WaveDTO
    {
        return WaveDTO::fromRequest($this);
    }
}
