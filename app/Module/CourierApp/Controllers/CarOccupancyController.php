<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\CourierApp\Commands\CarOccupancy\CreateCarOccupancyCommand;
use App\Module\CourierApp\Permissions\PermissionList;
use App\Module\CourierApp\Requests\CarOccupancy\CreateCarOccupancyRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

final class CarOccupancyController extends Controller
{
    /**
     * @OA\Post(
     *     path="/courier-app/order-take/car-occupancy",
     *     summary="Заполнености авто забора",
     *     description="Создать запись о заполнености авто забора",
     *     operationId="orderTakeCarOccupancy",
     *     tags={"Car Occupancy"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CreateCarOccupancyRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="success", type="boolean", example=true),
     *                 @OA\Property(property="message", type="string", example="Заполненость авто создана!"),
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
    public function orderTakeCarOccupancy(CreateCarOccupancyRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::CAR_OCCUPANCY_STORE);

        $this->dispatch(new CreateCarOccupancyCommand(
            (int)Auth::id(),
            $request->getOrderTakeDTO(),
        ));

        return (new MessagesResource(null))
            ->setMessage('Заполненость авто создана!');
    }
}
