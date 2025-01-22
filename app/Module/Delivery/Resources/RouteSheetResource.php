<?php

declare(strict_types=1);

namespace App\Module\Delivery\Resources;

use App\Helpers\DateHelper;
use App\Http\Resources\BaseJsonResource;
use App\Module\City\Resources\CityResource;
use App\Module\Courier\Resources\CourierShortInfoResource;
use App\Module\Delivery\Models\RouteSheet;
use Exception;

/**
 *
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="date", type="string", description="Дата марш листа", example="2023-09-22 12:46"),
 *     @OA\Property(property="number", type="string", description="Номер марш листа", example="000123123"),
 *     @OA\Property(property="sectors", type="array", description="Секторы в этом марш листе", @OA\Items(type="string", example="Сектор1")),
 *     @OA\Property(property="waves", type="array", description="Волны в этом марш листе", @OA\Items(type="string", example="Волна1")),
 *     @OA\Property(property="invoicesCount", type="integer", description="Кол-во накладных в марш листе", example="100"),
 *     @OA\Property(property="placesCount", type="integer", description="Кол-во мест в марш листе", example="100"),
 *     @OA\Property(property="weightSum", type="integer", description="Сумма физ веса в марш листе", example="100"),
 *     @OA\Property(property="volumeWeightSum", type="integer", description="Сумма обьемного веса в марш листе", example="100"),
 *     @OA\Property(property="status", description="Статус марш листа",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="В работе"),
 *     ),
 *     @OA\Property(
 *         property="courier",
 *         ref="#/components/schemas/CourierShortInfoResource"
 *     ),
 *     @OA\Property(
 *         property="city",
 *         ref="#/components/schemas/CityResource"
 *     ),
 * )
 *
 * @property RouteSheet $resource
 */

final class RouteSheetResource extends BaseJsonResource
{
    /**
     * @throws Exception
     */
    public function toArray($request): array
    {
        return [
            'id'              => $this->resource->id,
            'date'            => DateHelper::getDateWithTime($this->resource->created_at),
            'number'          => $this->resource->number,
            'courier'         => new CourierShortInfoResource($this->resource->courier),
            'city'            => new CityResource($this->resource->city, false),
            'sectors'         => $this->resource->routeSheetInvoices->pluck('invoice.receiver.sector.name')->unique()->values()->all(),
            'waves'           => $this->resource->routeSheetInvoices->pluck('invoice.wave.title')->unique()->values()->all(),
            'invoicesCount'   => $this->resource->routeSheetInvoices->count(),
            'placesCount'     => $this->resource->routeSheetInvoices->pluck('invoice.cargo.places')->sum(),
            'weightSum'       => round($this->resource->routeSheetInvoices->pluck('invoice.cargo.weight')->sum(), 2),
            'volumeWeightSum' => round($this->resource->routeSheetInvoices->pluck('invoice.cargo.volume_weight')->sum(), 2),
            'status'          => $this->resource->getStatusName()
        ];
    }
}
