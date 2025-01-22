<?php

declare(strict_types=1);

namespace App\Module\Monitoring\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Module\Monitoring\Contracts\Services\MonitoringService;
use App\Module\Monitoring\Requests\CourierInfoShowRequest;
use App\Module\Monitoring\Requests\DeliveryInfoShowRequest;
use App\Module\Monitoring\Requests\TakeInfoShowRequest;
use App\Module\Monitoring\Resources\CourierInfosResource;
use App\Module\Monitoring\Resources\DeliveryInfoShowResource;
use App\Module\Monitoring\Resources\TakeInfoShowResource;

final class MonitoringController extends Controller
{
    public function __construct(
        public readonly MonitoringService $monitoringService
    ) {
    }

    /**
     * @OA\Get (
     *    path="/monitoring/deliveries",
     *    tags={"Monitoring"},
     *    summary="Получить данные о количествах доставок",
     *    @OA\Parameter(
     *         name="X-User",
     *         in="header",
     *         required=true,
     *         @OA\Schema(type="integer",example=4)
     *    ),
     *   @OA\Parameter(
     *         name="dispatcherSectorId",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer",example=24)
     *    ),
     *    @OA\Parameter(
     *         name="createdAtFrom",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string",example="2023-01-01")
     *    ),
     *    @OA\Parameter(
     *         name="createdAtTo",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string",example="2023-08-01")
     *    ),
     *    @OA\Response(
     *        response=200,
     *        @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                @OA\Property(
     *                    property="data",
     *                    ref="#/components/schemas/DeliveryInfoShowResource"
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
     * @param DeliveryInfoShowRequest $request
     * @return DeliveryInfoShowResource
     */
    public function deliveries(DeliveryInfoShowRequest $request): DeliveryInfoShowResource
    {
        return (new DeliveryInfoShowResource(
            $this->monitoringService->getDeliverInfo($request->getDTO())
        ));
    }

    /**
     * @OA\Get (
     *    path="/monitoring/order-takes",
     *    tags={"Monitoring"},
     *    summary="Получить данные о количествах заборов",
     *
     *    @OA\Parameter(
     *         name="X-User",
     *         in="header",
     *         required=true,
     *         @OA\Schema(type="integer",example=4)
     *    ),
     *   @OA\Parameter(
     *         name="dispatcherSectorId",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer",example=24)
     *    ),
     *    @OA\Parameter(
     *         name="createdAtFrom",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string",example="2023-01-01")
     *    ),
     *    @OA\Parameter(
     *         name="createdAtTo",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string",example="2023-08-01")
     *    ),
     *    @OA\Parameter(
     *         name="takeDateFrom",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string",example="2023-08-01")
     *    ),
     *    @OA\Parameter(
     *         name="takeDateTo",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string",example="2023-08-01")
     *    ),
     *    @OA\Response(
     *        response=200,
     *        @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                @OA\Property(
     *                    property="data",
     *                    ref="#/components/schemas/TakeInfoShowResource"
     *                )
     *            )
     *        ),
     *        description=""
     *    ),
     *    security={{
     *        "bearer":{}
     *    }}
     * )
     * @param TakeInfoShowRequest $request
     * @return TakeInfoShowResource
     */
    public function orderTakes(TakeInfoShowRequest $request): TakeInfoShowResource
    {
        return (new TakeInfoShowResource(
            $this->monitoringService->getTakeInfo($request->getDTO())
        ));
    }

    /**
     * @OA\Get (
     *    path="/monitoring/couriers",
     *    tags={"Monitoring"},
     *    summary="Получить данные о количествах заборов и доставок по курьерам",
     *
     *    @OA\Parameter(
     *         name="X-User",
     *         in="header",
     *         required=true,
     *         @OA\Schema(type="integer",example=4)
     *    ),
     *   @OA\Parameter(
     *         name="dispatcherSectorId",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer",example=24)
     *    ),
     *    @OA\Parameter(
     *         name="createdAtFrom",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string",example="2023-01-01")
     *    ),
     *    @OA\Parameter(
     *         name="createdAtTo",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string",example="2023-08-01")
     *    ),
     *    @OA\Response(
     *        response=200,
     *        @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                @OA\Property(
     *                    property="data",
     *                    ref="#/components/schemas/CourierMonitoringInfoResource"
     *                )
     *            )
     *        ),
     *        description=""
     *    ),
     *    security={{
     *        "bearer":{}
     *    }}
     * )
     * @param CourierInfoShowRequest $request
     * @return CourierInfosResource
     */
    public function couriers(CourierInfoShowRequest $request): CourierInfosResource
    {
        return (new CourierInfosResource(
            $this->monitoringService->getCourierInfo($request->getDTO())
        ));
    }
}
