<?php

declare(strict_types=1);

namespace App\Module\Planning\Requests;

use App\Module\Planning\DTO\ContainerPaginationDTO;
use App\Module\Planning\DTO\ContainerShowDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     @OA\Property(property="waveId", type="integer", example=1, description="ID волны"),
 *     @OA\Property(property="statusId", type="integer", example=1, description="ID статуса"),
 *     @OA\Property(property="statusIds", @OA\Schema(type="array", @OA\Items(type="integer", example=1)), description="Массив ID статусов"),
 *     @OA\Property(property="date", type="string", example="2023-08-11", description="Дата"),
 *     @OA\Property(property="dateFrom", type="string", example="2023-08-11", description="Дата с"),
 *     @OA\Property(property="dateTo", type="string", example="2023-08-11", description="Дата по"),
 *     @OA\Property(property="cargoType", type="number", example="1", description="Тип груза (1 или 2)"),
 *     @OA\Property(property="sectorIds", @OA\Schema(type="array", @OA\Items(type="integer", example=1)), description="Массив ID секторов"),
 *     @OA\Property(property="invoiceNumber", type="string", description="Номер накладной"),
 *     @OA\Property(property="deliveryStatusIds", type="array", @OA\Items(type="integer"), description="Массив ID статусов доставки"),
 *     @OA\Property(property="userId", type="integer", description="ID пользователя"),
 *     @OA\Property(property="courierId", type="integer", description="ID курьера"),
 *     @OA\Property(property="title", type="string", description="Название"),
 *     @OA\Property(property="sectorId", type="integer", description="ID сектора"),
 * )
 */
final class ContainerShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'invoiceNumber'       => ['nullable', 'string'],
            'deliveryStatusIds'   => ['nullable', 'array'],
            'deliveryStatusIds.*' => ['required', 'integer'],
            'userId'              => ['nullable', 'integer'],
            'courierId'           => ['nullable', 'integer'],
            'title'               => ['nullable', 'string'],
            'sectorId'            => ['nullable', 'integer'],
            'waveId'              => ['nullable', 'integer'],
            'statusId'            => ['nullable', 'integer'],
            'statusIds'           => ['nullable', 'array'],
            'statusIds.*'         => ['required', 'integer'],
            'date'                => ['nullable', 'date_format:Y-m-d'],
            'dateFrom'            => ['nullable', 'date_format:Y-m-d'],
            'dateTo'              => ['nullable', 'date_format:Y-m-d'],
            'cargoType'           => ['nullable', 'number'],
            'sectorIds'           => ['nullable', 'array'],
            'sectorIds.*'         => ['required', 'integer'],
        ];
    }

    public function getDTO(): ContainerShowDTO
    {
        return ContainerShowDTO::fromRequest($this);
    }

    public function getPaginatedDTO(): ContainerPaginationDTO
    {
        return ContainerPaginationDTO::fromRequest($this);
    }
}
