<?php

declare(strict_types=1);

namespace App\Module\Courier\Resources;

use App\Helpers\DateHelper;
use App\Module\Car\Resources\CarResource;
use App\Module\Company\Resources\CompanyResource;
use App\Module\Courier\Models\Courier;
use App\Module\DispatcherSector\Resources\DispatcherSectorItemResource;
use App\Module\File\Resources\FileResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="iin", type="string", example="123123123123"),
 *     @OA\Property(property="fullName", type="string", example="Test Test"),
 *     @OA\Property(property="phoneNumber", type="string", example="+77777777777"),
 *     @OA\Property(property="createdAt", type="string", description="Created time"),
 *     @OA\Property(property="routingEnabled", type="bool", description="маршрутизация вкл/выкл", example="1"),
 *     @OA\Property(property="status", type="object", ref="#/components/schemas/CourierStatusResource"),
 *     @OA\Property(property="car", type="object", ref="#/components/schemas/CarResource"),
 *     @OA\Property(property="company", type="object", ref="#/components/schemas/CompanyResource"),
 *     @OA\Property(property="dispatcherSector", type="object", ref="#/components/schemas/DispatcherSectorItemResource"),
 *     @OA\Property(property="schedule", type="object", ref="#/components/schemas/CourierScheduleTypeResource"),
 *     @OA\Property(property="driverLicenses", @OA\Schema(type="array", @OA\Items(type="string", description="File url"))),
 *     @OA\Property(property="identificationCards", @OA\Schema(type="array", @OA\Items(type="string", description="File url"))),
 * )
 *
 * @property Courier $resource
 */
final class CourierResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                  => $this->resource->id,
            'iin'                 => $this->resource->iin,
            'fullName'            => $this->resource->full_name,
            'phoneNumber'         => $this->resource->phone_number,
            'routingEnabled'      => $this->resource->routing_enabled,
            'createdAt'           => DateHelper::getDateWithTime($this->resource->created_at),
            'status'              => new CourierStatusResource($this->resource->status),
            'car'                 => new CarResource($this->resource->car),
            'company'             => new CompanyResource($this->resource->company),
            'schedule'            => new CourierScheduleTypeResource($this->resource->scheduleType),
            'dispatcherSector'    => new DispatcherSectorItemResource($this->resource->dispatcherSector),
            'identificationCards' => FileResource::collection($this->resource->identificationCard),
            'driverLicenses'      => FileResource::collection($this->resource->driverLicense),
        ];
    }
}
