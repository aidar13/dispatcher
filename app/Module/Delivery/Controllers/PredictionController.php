<?php

declare(strict_types=1);

namespace App\Module\Delivery\Controllers;

use App\Http\Controllers\Controller;
use App\Module\Delivery\Contracts\Services\PredictionService;
use App\Module\Delivery\Permissions\PermissionList;
use App\Module\Delivery\Requests\PredictionRequest;
use App\Module\Delivery\Resources\CarPredictionReportResource;
use App\Module\Delivery\Resources\PredictionReportResource;
use Illuminate\Auth\Access\AuthorizationException;

final class PredictionController extends Controller
{
    public function __construct(
        private readonly PredictionService $predictionService
    ) {
    }

    /**
     * @OA\Get (
     *     path="/prediction",
     *     operationId="getPredictionReport",
     *     tags={"Prediction"},
     *     summary="Получение данных для прогнозирование",
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
     *
     *     @OA\Response(
     *         response=200,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/PredictionReportResource",
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
    public function index(PredictionRequest $request): PredictionReportResource
    {
        $this->authorize(PermissionList::PREDICTION_REPORT);

        return new PredictionReportResource(
            $this->predictionService->getReport($request->getDTO())
        );
    }

    /**
     * @OA\Get (
     *     path="/prediction/cars",
     *     operationId="getCarPredictionReport",
     *     tags={"Prediction"},
     *     summary="Получение данных прогнозирование авто",
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
     *
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="data",
     *                     ref="#/components/schemas/CarPredictionReportResource",
     *                 )
     *             )
     *         )
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @param PredictionRequest $request
     * @return CarPredictionReportResource
     * @throws AuthorizationException
     */
    public function cars(PredictionRequest $request): CarPredictionReportResource
    {
        $this->authorize(PermissionList::PREDICTION_REPORT);

        return new CarPredictionReportResource(
            $this->predictionService->getCarsReport($request->getDTO())
        );
    }
}
