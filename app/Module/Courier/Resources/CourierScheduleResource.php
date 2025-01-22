<?php

declare(strict_types=1);

namespace App\Module\Courier\Resources;

use App\Helpers\DateHelper;
use App\Http\Resources\BaseJsonResource;
use App\Module\Courier\Models\CourierSchedule;
use Carbon\Carbon;

/**
 * @property CourierSchedule $resource
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="weekday", type="integer", example=6),
 *     @OA\Property(property="workTimeFrom", type="string", example="12:00"),
 *     @OA\Property(property="workTimeUntil", type="string", example="18:00"),
 * )
 */
final class CourierScheduleResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->resource->id,
            'weekday'       => $this->resource->weekday,
            'workTimeFrom'  => DateHelper::getTime(Carbon::parse($this->resource->work_time_from)),
            'workTimeUntil' => DateHelper::getTime(Carbon::parse($this->resource->work_time_until)),
        ];
    }
}
