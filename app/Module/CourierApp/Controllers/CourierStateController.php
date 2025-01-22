<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\CourierApp\Commands\CourierState\CreateCourierStateCommand;
use App\Module\CourierApp\Permissions\PermissionList;
use App\Module\CourierApp\Requests\CourierState\CreateCourierStateRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

final class CourierStateController extends Controller
{
    /**
     * @OA\Post(
     *     path="/courier-app/order-take/here-state",
     *     summary="Курьер прибыл на забор",
     *     description="Курьер прибыл на забор",
     *     operationId="orderTakeHereState",
     *     tags={"Courier States"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CreateCourierStateRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="success", type="boolean", example=true),
     *                 @OA\Property(property="message", type="string", example="Курьер успешно прибыл на забора!"),
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
    public function orderTakeHereState(CreateCourierStateRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::COURIER_STATE_STORE);

        $this->dispatch(new CreateCourierStateCommand(
            (int)Auth::id(),
            $request->getOrderTakeDTO(),
        ));

        return (new MessagesResource(null))
            ->setMessage('Курьер успешно прибыл на забора!');
    }

    /**
     * @OA\Post(
     *     path="/courier-app/delivery/here-state",
     *     summary="Курьер прибыл на доставку",
     *     description="Курьер прибыл на доставку",
     *     operationId="deliveryHereState",
     *     tags={"Courier States"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CreateCourierStateRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="success", type="boolean", example=true),
     *                 @OA\Property(property="message", type="string", example="Курьер успешно прибыл на доставку!"),
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
    public function deliveryHereState(CreateCourierStateRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::COURIER_STATE_STORE);

        $this->dispatch(new CreateCourierStateCommand(
            (int)Auth::id(),
            $request->getDeliveryDTO(),
        ));

        return (new MessagesResource(null))
            ->setMessage('Курьер успешно прибыл на доставку!');
    }
}
