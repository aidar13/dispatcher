<?php

declare(strict_types=1);

namespace App\Module\Courier\Resources;

use App\Helpers\DateHelper;
use App\Http\Resources\BaseJsonResource;
use App\Module\Courier\Models\Courier;
use App\Module\Courier\Models\CourierScheduleType;
use App\Module\Status\Models\StatusType;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="fullName", type="string", description="Полное имя курьера", example="Байзакова Айжан"),
 *     @OA\Property(property="info", type="array",
 *         @OA\Items(
 *             @OA\Property(property="courierId", type="integer", description="Айди курьера", example="1656"),
 *             @OA\Property(property="date", type="string", description="дата работы", example="2023-08-08"),
 *             @OA\Property(property="takesTotal", type="integer", description="общее количество заборов", example="20"),
 *             @OA\Property(property="takesShipped", type="integer", description="курьер отгрузил на склад", example="10"),
 *             @OA\Property(property="deliveriesTotal", type="integer", description="общее количество доставок", example="20"),
 *             @OA\Property(property="deliveriesDelivered", type="integer", description="количество доставленных накладных", example="10"),
 *             @OA\Property(property="timeOfWork", type="string", description="отработанное время курьера за день", example="1 ч 5 м"),
 *             @OA\Property(property="cash", type="numeric", description="Наличка", example=10005.05),
 *             @OA\Property(property="codPayment", type="integer", description="Наложенный платеж", example=10005),
 *             @OA\Property(property="hasReturnDelivery", type="boolean", description="Есть ли возвратные накладные", example=true),
 *             @OA\Property(property="isClosed", type="boolean", description="Закрыт ли день курьера", example=true),
 *             @OA\Property(property="waves", type="array", description="В каких волнах он работал", @OA\Items(type="string",example="Volna 1"),
 *         )
 *     )),
 * )
 *
 * @property Courier $resource
 */
final class CourierEndOfDayResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->resource->id,
            'fullName'        => $this->resource->full_name,
            'info'            => $this->resource->info,
        ];
    }
}
