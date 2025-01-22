<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\Courier\Contracts\Services\CourierService;
use App\Module\CourierApp\Permissions\PermissionList;
use App\Module\CourierApp\Resources\Courier\CourierInfoResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

final class CourierController extends Controller
{
    public function __construct(
        private readonly CourierService $service
    ) {
    }

    /**
     * @OA\Get (
     *     path="/courier-app/profile",
     *     tags={"Courier Info"},
     *     operationId="getCourierByUserId",
     *     summary="Подробный просмотр информации о курьере",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Response(
     *         response=200,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/CourierInfoResource",
     *             )
     *         )
     *     ),
     *         description=""
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     *
     * @throws AuthorizationException
     */
    public function profile(): CourierInfoResource
    {
        $this->authorize(PermissionList::COURIER_PROFILE);

        return new CourierInfoResource(
            $this->service->getCourierByUserId((int)Auth::id())
        );
    }

    /**
     * @OA\Get (
     *     path="/courier-app/check-by-phone/{phone}",
     *     tags={"Courier Info"},
     *     operationId="checkByPhone",
     *     summary="Проверка курьера для входа в приложения",
     *
     *     @OA\Response(
     *         response=200,
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Success!"),
     *             @OA\Property(property="data", type="object",example=null),
     *             @OA\Property(property="code", type="integer", example=200),
     *         ),
     *         description="",
     *    ),
     * )
     */
    public function checkByPhone(string $phone): MessagesResource
    {
        $this->service->getCourierByPhone($phone);

        return (new MessagesResource(null))
            ->setMessage('Success!');
    }
}
