<?php

declare(strict_types=1);

namespace App\Module\Routing\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\Routing\Commands\CreateCourierRoutingCommand;
use App\Module\Routing\Contracts\Services\RoutingService;
use App\Module\Routing\Permissions\PermissionList;
use App\Module\Routing\Requests\CreateCourierRoutingRequest;
use App\Module\Routing\Resources\RoutingItemsResource;
use Illuminate\Auth\Access\AuthorizationException;

final class RoutingController extends Controller
{
    public function __construct(private readonly RoutingService $service)
    {
    }

    /**
     * @OA\Get (
     *     path="/routing/courier/{courierId}",
     *     summary="Получить текущие заказы/накладные для маршрута курьеру для МПК",
     *     description="Получить текущие заказы/накладные для маршрута курьеру для МПК",
     *     operationId="courierRouting",
     *     tags={"Courier Routing"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="data",
     *                     ref="#/components/schemas/RoutingItemResource",
     *                 )
     *             )
     *         ),
     *     ),
     *     security={{
     *         "bearer": {}
     *     }}
     * )
     */
    public function getByCourier(int $courierId): RoutingItemsResource
    {
        return new RoutingItemsResource(
            $this->service->getAllByCourierId($courierId)
        );
    }

    /**
     * @OA\Post(
     *     path="/routing",
     *     summary="Построить маршрут курьеру по текущим заказам/накладным",
     *     description="Построить маршрут курьеру по текущим заказам/накладным",
     *     operationId="courierRouting",
     *     tags={"Courier Routing"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CreateCourierRoutingRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="success", type="boolean", example=true),
     *                 @OA\Property(property="message", type="string", example="Успешный ответ!"),
     *                 @OA\Property(property="data", type="object", example=null),
     *                 @OA\Property(property="code",type="integer", example=201)
     *             )
     *         )
     *     ),
     *     security={{
     *         "bearer": {}
     *     }}
     * )
     * @throws AuthorizationException
     */
    public function create(CreateCourierRoutingRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::ROUTING_STORE);

        $this->dispatchSync(new CreateCourierRoutingCommand(
            $request->getDTO()
        ));

        return (new MessagesResource(null))
            ->setMessage('Успешный ответ!');
    }
}
