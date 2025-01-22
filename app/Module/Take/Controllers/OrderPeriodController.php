<?php

declare(strict_types=1);

namespace App\Module\Take\Controllers;

use App\Http\Controllers\Controller;
use App\Module\Take\Contracts\Services\OrderPeriodService;
use App\Module\Take\Requests\OrderPeriodRequest;
use App\Module\Take\Resources\OrderPeriodsResource;

final class OrderPeriodController extends Controller
{
    public function __construct(
        private readonly OrderPeriodService $service
    ) {
    }

    /**
     * @OA\Get (
     *     path="/order-period",
     *     tags={"Период заказа"},
     *     summary="Список периода заказов",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__limit"),
     *     @OA\Parameter (ref="#/components/parameters/__page"),
     *     @OA\Response(
     *         response=200,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/OrderPeriodResource",
     *             )
     *         )
     *     ),
     *         description=""
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     */
    public function index(OrderPeriodRequest $request): OrderPeriodsResource
    {
        return new OrderPeriodsResource(
            $this->service->getAll($request->getDTO())
        );
    }
}
