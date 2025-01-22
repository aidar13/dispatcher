<?php

declare(strict_types=1);

namespace App\Module\Planning\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Car\Resources\CarResource;
use App\Module\Courier\Models\Courier;
use App\Module\Courier\Resources\CourierScheduleResource;
use App\Module\DispatcherSector\Resources\SectorShowResource;
use Illuminate\Support\Carbon;

/**
 * @OA\Schema(
 *     @OA\Property(property="courierId", type="integer", example=1),
 *     @OA\Property(property="name", type="string", description="ФИО курьера", example="Абзал Азат Абзалулы"),
 *     @OA\Property(property="phoneNumber", type="string", description="Ноиер телефона курьера", example="+77025374330"),
 *     @OA\Property(property="invoiceQuantity", type="integer", description="Количество назначенных накладных", example=1),
 *     @OA\Property(property="fullness", type="string", description="Процент заполненности", example="22%"),
 *     @OA\Property(
 *         property="schedule",
 *         ref="#/components/schemas/CourierScheduleResource"
 *     ),
 *     @OA\Property(
 *         property="sectors",
 *         ref="#/components/schemas/SectorShowResource"
 *     ),
 * )
 *
 * @property Courier $resource
 */
final class PlanningCourierShowResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'courierId'       => $this->resource->id,
            'name'            => $this->resource->full_name,
            'phoneNumber'     => $this->resource->phone_number,
            'carType'         => $this->resource->car->carType->title,
            'invoiceQuantity' => $this->resource->containerInvoices->count(),
            'fullness'        => $this->resource->getFullness(),
            'schedule'        => new CourierScheduleResource($this->resource->schedules->where('weekday', Carbon::parse($request->get('date'))->dayOfWeek)->first()),
            'sectors'         => SectorShowResource::collection($this->resource->containerSectors)
        ];
    }
}
