<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Resources\Courier;

use App\Http\Resources\BaseJsonResource;
use App\Module\Car\Resources\CarResource;
use App\Module\Courier\Models\Courier;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", description="ID курьера", example=1),
 *     @OA\Property(property="userId", type="integer", description="ID пользователя курьера", example=1),
 *     @OA\Property(property="fullName", type="string", description="ФИО", example="Дмитрий БМВ123"),
 *     @OA\Property(property="iin", type="string", description="ИИН", example="020128500746"),
 *     @OA\Property(property="phone", type="string", description="Номер телефона", example="1"),
 *     @OA\Property(property="routingEnabled", type="bool", description="маршрутизация вкл/выкл", example="1"),
 *     @OA\Property(property="cityName", type="string", description="Город", example="Алматы"),
 *     @OA\Property(property="latitude", type="float", description="широта", example="43.25"),
 *     @OA\Property(property="longitude", type="float", description="долгота", example="76.95"),
 *     @OA\Property(
 *         property="car",
 *         ref="#/components/schemas/CarResource"
 *     ),
 * )
 * @property Courier $resource
 */
final class CourierInfoResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->resource->id,
            'userId'         => $this->resource->user_id,
            'fullName'       => $this->resource->full_name,
            'iin'            => $this->resource->iin,
            'phone'          => $this->resource->phone_number,
            'routingEnabled' => $this->resource->routing_enabled,
            'cityName'       => $this->resource->dispatcherSector->city->name,
            'latitude'       => $this->resource->dispatcherSector->city->latitude,
            'longitude'      => $this->resource->dispatcherSector->city->longitude,
            'car'            => new CarResource($this->resource->car, false),
        ];
    }
}
