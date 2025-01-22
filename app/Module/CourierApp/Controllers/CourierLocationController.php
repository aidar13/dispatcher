<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\CourierApp\Commands\CourierLocation\CreateCourierLocationCommand;
use App\Module\CourierApp\Permissions\PermissionList;
use App\Module\CourierApp\Requests\CourierLocation\CreateCourierLocationRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

final class CourierLocationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/courier-app/courier-locations",
     *     summary="Сохранение координаты курьера",
     *     description="Сохранение координаты курьера",
     *     operationId="saveCourierLoaction",
     *     tags={"Courier Location"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CreateCourierLocationRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="success", type="boolean", example=true),
     *                 @OA\Property(property="message", type="string", example="Координаты успешно сохранены!"),
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
    public function store(CreateCourierLocationRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::COURIER_LOCATION_STORE);

        $this->dispatch(new CreateCourierLocationCommand(
            (int)Auth::id(),
            $request->getDTO(),
        ));

        return (new MessagesResource(null))
            ->setMessage('Координаты успешно сохранены!');
    }
}
