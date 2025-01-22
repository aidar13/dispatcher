<?php

declare(strict_types=1);

namespace App\Module\Planning\Controllers;

use App\Http\Controllers\Controller;
use App\Module\Courier\Contracts\Services\CourierService;
use App\Module\Planning\Contracts\Services\PlanningService;
use App\Module\Planning\Permissions\PermissionList;
use App\Module\Planning\Requests\PlanningCourierRequest;
use App\Module\Planning\Requests\PlanningRequest;
use App\Module\Planning\Resources\PlanningCourierResource;
use App\Module\Planning\Resources\PlanningResource;
use Illuminate\Auth\Access\AuthorizationException;

final class PlanningController extends Controller
{
    public function __construct(
        private readonly PlanningService $service,
        private readonly CourierService $courierService
    ) {
    }

    /**
     * @OA\Get (
     *     path="/planning",
     *     operationId="getPlanning",
     *     tags={"Planning"},
     *     summary="Планирование",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\Parameter (
     *         name="dispatcherSectorId",
     *         in="query",
     *         description="ID диспетчер сектора",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example="1",
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="waveId",
     *         in="query",
     *         description="ID волны",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example="1",
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="sectorIds",
     *         in="query",
     *         description="ID секторов",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="integer", example=1))
     *     ),
     *     @OA\Parameter (
     *         name="statusId",
     *         in="query",
     *         description="Статус груза (1 если Фактический,2 если Прибывающий)",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example="1",
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="date",
     *         in="query",
     *         description="Дата планирование",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="2023-08-18",
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="invoiceNumber",
     *         in="query",
     *         description="Номер накладной",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="SP0000001",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/PlanningShowResource",
     *             )
     *         )
     *     ),
     *         description=""
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @throws AuthorizationException
     */
    public function index(PlanningRequest $request): PlanningResource
    {
        $this->authorize(PermissionList::PLANNING_INDEX);

        return new PlanningResource(
            $this->service->getSectors($request->getDTO())
        );
    }

    /**
     * @OA\Get (
     *     path="/planning/couriers",
     *     operationId="getPlanningCouriers",
     *     tags={"Planning"},
     *     summary="Курьеры планирования",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\Parameter (
     *         name="dispatcherSectorId",
     *         in="query",
     *         description="ID диспетчер сектора",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example="1",
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="waveId",
     *         in="query",
     *         description="ID волны",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example="1",
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="date",
     *         in="query",
     *         description="Дата планирование",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="2023-08-18",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/PlanningCourierShowResource",
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
     * @param PlanningCourierRequest $request
     * @return PlanningCourierResource
     * @throws AuthorizationException
     */
    public function courierIndex(PlanningCourierRequest $request): PlanningCourierResource
    {
        $this->authorize(PermissionList::PLANNING_COURIER_INDEX);

        return new PlanningCourierResource(
            $this->courierService->getCouriersByWaveIdAndDate($request->getDTO())
        );
    }
}
