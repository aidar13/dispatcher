<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\CourierApp\Commands\CourierCall\CreateCourierCallCommand;
use App\Module\CourierApp\Permissions\PermissionList;
use App\Module\CourierApp\Requests\CourierCall\CreateCourierCallRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

final class CourierCallController extends Controller
{
    /**
     * @OA\Post(
     *     path="/courier-app/order-take/courier-call",
     *     summary="Сохранение истории звонка курьера при заборе",
     *     operationId="orderTakeCourierCall",
     *     tags={"Courier Call"},
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CreateCourierCallRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Успешный ответ"),
     *             @OA\Property(property="data", type="object", example=null),
     *             @OA\Property(property="code", type="integer", example="200"),
     *         )
     *     ),
     *
     *     security={{
     *         "bearer": {}
     *     }}
     * )
     * @param CreateCourierCallRequest $request
     * @return MessagesResource
     * @throws AuthorizationException
     */
    public function orderTakeCourierCall(CreateCourierCallRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::COURIERS_CALL_STORE);

        $this->dispatch(new CreateCourierCallCommand((int)Auth::id(), $request->getOrderTakeDTO()));

        return (new MessagesResource(null))
            ->setMessage('Успешный ответ');
    }

    /**
     * @OA\Post(
     *     path="/courier-app/delivery/courier-call",
     *     summary="Сохранение истории звонка курьера при доставке",
     *     operationId="deliveryCourierCall",
     *     tags={"Courier Call"},
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CreateCourierCallRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Успешный ответ"),
     *             @OA\Property(property="data", type="object", example=null),
     *             @OA\Property(property="code", type="integer", example="200"),
     *         )
     *     ),
     *
     *     security={{
     *         "bearer": {}
     *     }}
     *  )
     * @param CreateCourierCallRequest $request
     * @return MessagesResource
     * @throws AuthorizationException
     */
    public function deliveryCourierCall(CreateCourierCallRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::COURIERS_CALL_STORE);

        $this->dispatch(new CreateCourierCallCommand((int)Auth::id(), $request->getDeliveryDTO()));

        return (new MessagesResource(null))
            ->setMessage('Успешный ответ');
    }
}
