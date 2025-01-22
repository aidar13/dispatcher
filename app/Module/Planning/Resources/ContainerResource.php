<?php

declare(strict_types=1);

namespace App\Module\Planning\Resources;

use App\Helpers\NumberHelper;
use App\Http\Resources\BaseJsonResource;
use App\Module\Courier\Resources\CourierShortInfoResource;
use App\Module\Planning\DTO\ContainerDTO;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", description="Название контейнера", example="Контейнер 1"),
 *     @OA\Property(property="date", type="string", description="Дата доставки контейнера", example="2023-08-18"),
 *     @OA\Property(property="stopsCount", type="integer", example="10"),
 *     @OA\Property(property="invoicesCount", type="integer", example="10"),
 *     @OA\Property(property="places", type="integer", example="10"),
 *     @OA\Property(property="weight", type="numeric", example="10.1"),
 *     @OA\Property(property="volumeWeight", type="numeric", example="10.2"),
 *     @OA\Property(property="fastDeliveryCourier", type="string", description="Наименование сервиса доставки", example="Raketa"),
 *     @OA\Property(property="fastDeliveryId", type="integer", description="Идентификатор заказа быстрой доставки", example="1"),
 *     @OA\Property(property="fastDeliveryProviderId", type="integer", description="Идентификатор провайдера", example="1"),
 *     @OA\Property(
 *         property="invoices",
 *         ref="#/components/schemas/ContainerInvoiceResource"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         ref="#/components/schemas/ContainerStatusResource"
 *     ),
 *     @OA\Property(
 *         property="courier",
 *         ref="#/components/schemas/CourierShortInfoResource"
 *     ),
 * )
 *
 * @property ContainerDTO $resource
 */
final class ContainerResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                     => $this->resource->id,
            'title'                  => $this->resource->title,
            'places'                 => $this->resource->places,
            'weight'                 => NumberHelper::getRounded($this->resource->weight),
            'volumeWeight'           => NumberHelper::getRounded($this->resource->volumeWeight),
            'stopsCount'             => $this->resource->stopsCount,
            'invoicesCount'          => $this->resource->invoicesCount,
            'invoices'               => $this->resource->invoices,
            'courier'                => new CourierShortInfoResource($this->resource->courier),
            'status'                 => new ContainerStatusResource($this->resource->status),
            'fastDeliveryPrice'      => $this->resource->fastDeliveryPrice,
            'fastDeliveryStatus'     => $this->resource->fastDeliveryStatus,
            'fastDeliveryCourier'    => $this->resource->fastDeliveryCourier,
            'fastDeliveryPhone'      => $this->resource->fastDeliveryPhone,
            'trackingUrl'            => $this->resource->trackingUrl,
            'fastDeliveryId'         => $this->resource->fastDeliveryId,
            'fastDeliveryProviderId' => $this->resource->fastDeliveryType
        ];
    }
}
