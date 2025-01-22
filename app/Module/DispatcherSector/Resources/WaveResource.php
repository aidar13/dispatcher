<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Resources;

use App\Helpers\DateHelper;
use App\Module\DispatcherSector\Models\Wave;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Волна 1"),
 *     @OA\Property(property="dispatcherSectorId", type="integer", example=1),
 *     @OA\Property(property="fromTime", type="string", example="09:00"),
 *     @OA\Property(property="toTime", type="string", example="14:00"),
 *     @OA\Property(property="date", type="string", example="2023-08-11")
 * )
 * @property Wave $resource
 */
final class WaveResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                 => $this->resource->id,
            'title'              => $this->resource->title,
            'dispatcherSectorId' => $this->resource->dispatcher_sector_id,
            'fromTime'           => DateHelper::getTime(Carbon::make($this->resource->from_time)),
            'toTime'             => DateHelper::getTime(Carbon::make($this->resource->to_time)),
            'date'               => $this->resource->getPlanningDate(),
        ];
    }
}
