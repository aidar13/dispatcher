<?php

declare(strict_types=1);

namespace App\Module\Courier\Requests;

use App\Module\Courier\DTO\CourierTakeListShowDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"date"},
 *
 *     @OA\Property(property="limit",type="integer", example=20, description="лимит"),
 *     @OA\Property(property="page",type="integer", example=1, description="страница"),
 *     @OA\Property(property="statusIds",description="Массив статусов айди", @OA\Schema(type="array", @OA\Items(type="integer", example=1))),
 *     @OA\Property(property="dispatcherSectorId",type="integer", example=1,description="Айди диспетчер сектора"),
 *     @OA\Property(property="scheduleTypeId",type="integer", example=1,description="Айди расписания"),
 *     @OA\Property(property="sectorIds",description="Массив Айди секторов", @OA\Schema(type="array", @OA\Items(type="integer", example=1))),
 * )
 */
final class CourierTakeListShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'limit'              => ['nullable', 'integer'],
            'page'               => ['nullable', 'integer'],
            'statusIds'          => ['nullable', 'array'],
            'dispatcherSectorId' => ['nullable', 'int'],
            'scheduleTypeId'     => ['nullable', 'int'],
            'sectorIds'          => ['nullable', 'array'],
        ];
    }

    public function getDTO(): CourierTakeListShowDTO
    {
        return CourierTakeListShowDTO::fromRequest($this);
    }
}
