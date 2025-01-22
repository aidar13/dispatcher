<?php

declare(strict_types=1);

namespace App\Module\Courier\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Module\Courier\Commands\CloseCourierDayCommand;
use App\Module\Courier\Contracts\Services\CourierReportService;
use App\Module\Courier\Permissions\PermissionList;
use App\Module\Courier\Requests\CourierCloseDayRequest;
use App\Module\Courier\Requests\CourierReportRequest;
use App\Module\Courier\Requests\CourierReportShowRequest;
use App\Module\Courier\Resources\CloseCourierDayResource;
use App\Module\Courier\Resources\CourierEndOfDayShowResource;
use App\Module\Courier\Resources\CourierEndOfDaysResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

final class CourierReportController extends Controller
{
    public function __construct(
        private readonly CourierReportService $service
    ) {
    }

    /**
     * @OA\Get(
     *     path="/courier-report/end-of-day",
     *     summary="Список завершения дня курьеров",
     *     operationId="getAllEndOfDay",
     *     tags={"Courier"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__limit"),
     *     @OA\Parameter (ref="#/components/parameters/__page"),
     *
     *     @OA\Parameter(
     *         name="fromDate",
     *         in="query",
     *         required=true,
     *         description="Created at from",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="toDate",
     *         in="query",
     *         required=false,
     *         description="Created at until",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="courierId",
     *         in="query",
     *         required=false,
     *         description="ID курьера",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="dispatcherSectorId",
     *         in="query",
     *         required=false,
     *         description="ID диспетчер сектора",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="hasReturn",
     *         in="query",
     *         required=false,
     *         description="Есть ли возврат выдачи (1=Нет,2=Да, null=все)",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="hasCash",
     *         in="query",
     *         required=false,
     *         description="Есть ли наличка (1=Нет,2=Да, null=все)",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="hasCodPayment",
     *         in="query",
     *         required=false,
     *         description="Есть ли налож платеж (1=Нет,2=Да, null=все)",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example=null),
     *             @OA\Property(property="code", type="integer", example="200"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/CourierEndOfDayResource")
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     * @throws AuthorizationException
     */
    public function index(CourierReportRequest $request): CourierEndOfDaysResource
    {
        $this->authorize(PermissionList::COURIER_REPORT);
        $couriers = $this->service->getCourierEndOfDayPaginated($request->getDTO());

        return new CourierEndOfDaysResource($couriers);
    }

    /**
     * @OA\Get(
     *     path="/courier-report/{courierId}/end-of-day",
     *     summary="Детальный просмотр завершения дня курьера",
     *     operationId="getCourierEndOfDay",
     *     tags={"Courier"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__limit"),
     *     @OA\Parameter (ref="#/components/parameters/__page"),
     *
     *     @OA\Parameter(
     *         name="courierId",
     *         in="path",
     *         required=true,
     *         description="Courier Id",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         required=true,
     *         description="Дата дня курьера для завершении дня",
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example=null),
     *             @OA\Property(property="code", type="integer", example="200"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/CourierEndOfDayShowResource")
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     * @throws AuthorizationException
     */
    public function show(int $courierId, CourierReportShowRequest $request): CourierEndOfDayShowResource
    {
        $this->authorize(PermissionList::COURIER_REPORT);

        $courier = $this->service->getCourierEndOfDay(
            $courierId,
            $request->input('date')
        );

        return new CourierEndOfDayShowResource($courier);
    }

    /**
     * @OA\Post(
     *     path="/courier-report/{courierId}/close-day",
     *     tags={"Courier"},
     *     operationId="SaveCloseDayOfCourier",
     *     summary="Закрытие дня для курьера",
     *
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CourierCloseDayRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Закрытия дня курьера выполнен!"),
     *             @OA\Property(property="data", type="object",example=null),
     *             @OA\Property(property="code", type="integer", example=200),
     *         ),
     *     description="",
     *     ),
     * )
     * @param int $courierId
     * @param CourierCloseDayRequest $request
     * @return CloseCourierDayResource
     * @throws AuthorizationException
     */
    public function closeDay(int $courierId, CourierCloseDayRequest $request): CloseCourierDayResource
    {
        $this->authorize(PermissionList::COURIER_CLOSE_DAY);

        $dto = $this->dispatch(new CloseCourierDayCommand(
            $courierId,
            (int)Auth::id(),
            $request->input('date')
        ));

        return (new CloseCourierDayResource($dto))
            ->setMessage('Закрытия дня курьера выполнен!');
    }
}
