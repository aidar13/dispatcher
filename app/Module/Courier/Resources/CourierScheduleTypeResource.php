<?php

declare(strict_types=1);

namespace App\Module\Courier\Resources;

use App\Helpers\DateHelper;
use App\Http\Resources\BaseJsonResource;
use App\Module\Courier\Models\CourierScheduleType;
use Illuminate\Support\Carbon;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string", description="Название смены"),
 *     @OA\Property(property="workTimeFrom", type="string", description="Время начало смены"),
 *     @OA\Property(property="workTimeUntil", type="string", description="Время конец смены"),
 *     @OA\Property(property="shiftId", type="int", description="ID смены 1/2"),
 *     @OA\Property(property="shift", type="string", description="На смене/Завершил смену"),
 * )
 *
 * @property CourierScheduleType $resource
 */
final class CourierScheduleTypeResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->resource->id,
            'title'         => $this->resource->title,
            'workTimeFrom'  => DateHelper::getTime(Carbon::make($this->resource->work_time_from)),
            'workTimeUntil' => DateHelper::getTime(Carbon::make($this->resource->work_time_until)),
            'shiftId'       => $this->resource->shift_id,
            'shift'         => $this->resource->shift,
        ];
    }
}
