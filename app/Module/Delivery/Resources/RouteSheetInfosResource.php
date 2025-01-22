<?php

declare(strict_types=1);

namespace App\Module\Delivery\Resources;

use App\Helpers\DateHelper;
use App\Http\Resources\BaseJsonResource;
use App\Module\Courier\Resources\CourierShortInfoResource;
use App\Module\Delivery\Models\RouteSheet;
use Exception;

/**
 *
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="date", type="string", description="Дата марш листа", example="2023-09-22 12:46"),
 *     @OA\Property(property="number", type="string", description="Номер марш листа", example="000123123"),
 *     @OA\Property(
 *         property="invoices",
 *         ref="#/components/schemas/RouteSheetInvoicesInfoResource"
 *     ),
 *     @OA\Property(
 *         property="courier",
 *         ref="#/components/schemas/CourierShortInfoResource"
 *     ),
 * )
 *
 * @property RouteSheet $resource
 */

final class RouteSheetInfosResource extends BaseJsonResource
{
    /**
     * @throws Exception
     */
    public function toArray($request): array
    {
        return [
            'id'       => $this->resource->id,
            'date'     => DateHelper::getDateWithTime($this->resource->created_at),
            'number'   => $this->resource->number,
            'courier'  => new CourierShortInfoResource($this->resource->courier),
            'invoices' => RouteSheetInvoicesInfoResource::collection($this->resource->routeSheetInvoices)
        ];
    }
}
