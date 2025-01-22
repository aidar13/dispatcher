<?php

declare(strict_types=1);

namespace App\Module\Status\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Status\Models\WaitListStatus;

/**
 * @OA\Schema(
 *      @OA\Property(property="id", type="integer"),
 *      @OA\Property(property="name", type="string"),
 * )
 *
 * @property WaitListStatus $resource
 */
final class WaitListStatusStateResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'   => $this->resource->state_id,
            'name' => $this->resource->getStateName(),
        ];
    }
}
