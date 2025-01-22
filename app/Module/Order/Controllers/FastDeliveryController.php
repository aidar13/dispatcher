<?php

declare(strict_types=1);

namespace App\Module\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\Order\Commands\SetFastDeliveryCourierCommand;
use App\Module\Order\Permissions\PermissionList;
use App\Module\Order\Requests\SetFastDeliveryCourierRequest;
use Illuminate\Auth\Access\AuthorizationException;

final class FastDeliveryController extends Controller
{
    /**
     * @OA\Put (
     *     path="/fast-delivery-orders/{internalId}/set-courier",
     *     tags={"FastDelivery"},
     *     summary="Назначить курьера быстрой доставки",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/SetFastDeliveryCourierRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *        response=200,
     *        description="",
     *        @OA\JsonContent(
     *            @OA\Property(property="success", type="boolean", example=true),
     *            @OA\Property(property="message", type="string", example="Успешно назначен курьер"),
     *            @OA\Property(property="data", type="object",example=null),
     *            @OA\Property(property="code", type="integer", example=200),
     *        ),
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @throws AuthorizationException
     */
    public function assignCourier(int $internalId, SetFastDeliveryCourierRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::SET_FAST_DELIVERY_COURIER);

        $this->dispatch(new SetFastDeliveryCourierCommand($internalId, $request->getDTO()));

        return (new MessagesResource(null))->setMessage('Успешно назначен курьер!');
    }
}
