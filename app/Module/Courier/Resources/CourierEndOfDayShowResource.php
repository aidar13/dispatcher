<?php

declare(strict_types=1);

namespace App\Module\Courier\Resources;

use App\Module\Courier\Models\Courier;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="fullName", type="string", description="Полное имя курьера", example="Байзакова Айжан"),
 *     @OA\Property(property="info", type="object",
 *         @OA\Property(property="date", type="string", description="дата работы", example="2023-08-08"),
 *         @OA\Property(property="takesTotal", type="integer", description="общее количество заборов", example="20"),
 *         @OA\Property(property="takesShipped", type="integer", description="курьер отгрузил на склад", example="10"),
 *         @OA\Property(property="deliveriesTotal", type="integer", description="общее количество доставок", example="20"),
 *         @OA\Property(property="deliveriesDelivered", type="integer", description="количество доставленных накладных", example="10"),
 *         @OA\Property(property="returnDeliveryCount", type="integer", description="количество возврат выдачи", example="10"),
 *         @OA\Property(property="cancelledTakes", type="integer", description="Отмены за день (забор)", example="10"),
 *         @OA\Property(property="timeOfWork", type="string", description="отработанное время курьера за день", example="1 ч 5 м"),
 *         @OA\Property(property="cash", type="numeric", description="Наличка", example=10005.05),
 *         @OA\Property(property="codPayment", type="integer", description="Наложенный платеж", example=10005),
 *         @OA\Property(property="hasReturnDelivery", type="boolean", description="Есть ли возвратные накладные", example=true),
 *         @OA\Property(property="isClosed", type="boolean", description="Закрыт ли день курьера", example=true),
 *         @OA\Property(property="costForRoad", type="integer", description="Цена за дорогу", example="123"),
 *         @OA\Property(property="costForParking", type="integer", description="Цена за парковку ", example="123"),
 *         @OA\Property(property="detailsOfOrders", type="array", description="Детали заказов",
 *             @OA\Items(
 *                 @OA\Property(property="wave", type="string", description="Волна", example="Volna 1"),
 *                 @OA\Property(property="routeSheet", type="string", description="Номер маршрутного листа", example="numberOfRouteSheet"),
 *                 @OA\Property(property="isTake", type="boolean", description="Флаг получения заказа", example=true),
 *                 @OA\Property(property="isDelivery", type="boolean", description="Флаг доставки", example=false),
 *                 @OA\Property(property="orderNumber", type="string", description="Номер заказа", example="1"),
 *                 @OA\Property(property="weight", type="integer", description="Физ Вес заказа", example=1),
 *                 @OA\Property(property="invoiceNumber", type="string", description="Номер накладной", example="SP001"),
 *                 @OA\Property(property="address", type="string", description="Адрес", example="1"),
 *                 @OA\Property(property="cash", type="number", format="float", description="Сумма наличных", example=1000.02),
 *                 @OA\Property(property="codPayment", type="integer", description="Сумма наложенного платежа", example=1000),
 *                 @OA\Property(property="payerCompanyName", type="string", description="Название компании плательщика", example="Spark Logistica")
 *             )
 *         ),
 *         @OA\Property(property="waves", type="array", description="В каких волнах он работал", @OA\Items(type="string",example="Volna 1"),
 *     )),
 * )
 *
 * @property Courier $resource
 */
final class CourierEndOfDayShowResource extends JsonResource
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
