<?php

declare(strict_types=1);

namespace App\Module\Courier\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\Courier\Commands\CreateCourierScheduleCommand;
use App\Module\Courier\Contracts\Services\CourierScheduleService;
use App\Module\Courier\Permissions\PermissionList;
use App\Module\Courier\Requests\CreateCourierScheduleRequest;
use App\Module\Courier\Resources\CourierSchedulesResource;
use Illuminate\Auth\Access\AuthorizationException;

final class CourierScheduleController extends Controller
{
    public function __construct(
        private readonly CourierScheduleService $service
    ) {
    }

    /**
     * @OA\Post (
     *     path="/courier-schedule",
     *     summary="Создание графика работы курьера",
     *     tags={"Courier"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/CreateCourierScheduleRequest")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Расписание курьера создана"),
     *              @OA\Property(property="data", type="object",example=null),
     *              @OA\Property(property="code", type="integer", example=200),
     *          ),
     *      description="",
     *      ),
     *      security={{
     *          "apiKey":{}
     *      }}
     * )
     *
     * @param CreateCourierScheduleRequest $request
     * @return MessagesResource
     * @throws AuthorizationException
     */
    public function store(CreateCourierScheduleRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::COURIER_SCHEDULE_STORE);

        $this->dispatch(new CreateCourierScheduleCommand(
            $request->getDTO()
        ));

        return (new MessagesResource(null))
            ->setMessage('Расписание курьера создана');
    }

    /**
     * @OA\Get (
     *     path="/courier-schedule/{courierId}",
     *     summary="Получить расписание курьера по его id",
     *     tags={"Courier"},
     *
     *    @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *    @OA\Parameter(
     *        name="courierId",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *            type="integer",
     *            example=1
     *        )
     *    ),
     *
     *    @OA\Response(
     *        response=200,
     *        @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                @OA\Property(
     *                    property="data",
     *                    ref="#/components/schemas/CourierScheduleResource"
     *                )
     *            )
     *        ),
     *        description=""
     *    ),
     *    security={{
     *        "bearer":{}
     *    }}
     * )
     *
     *
     * @param int $courierId
     * @return CourierSchedulesResource
     * @throws AuthorizationException
     */
    public function show(int $courierId): CourierSchedulesResource
    {
        $this->authorize(PermissionList::COURIER_SCHEDULE_SHOW);

        return (new CourierSchedulesResource(
            $this->service->getScheduleByCourierId($courierId)
        ));
    }
}
