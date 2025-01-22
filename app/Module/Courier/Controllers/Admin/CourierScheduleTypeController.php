<?php

declare(strict_types=1);

namespace App\Module\Courier\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Module\Courier\Contracts\Services\CourierScheduleTypeService;
use App\Module\Courier\Resources\CourierScheduleTypesResource;

final class CourierScheduleTypeController extends Controller
{
    public function __construct(public CourierScheduleTypeService $scheduleService)
    {
    }

    /**
     * @OA\Get(
     *     path="/courier-schedule-types",
     *     summary="Список графиков курьеров",
     *     operationId="getAllCourierScheduleTypes",
     *     tags={"Courier"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__limit"),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example=null),
     *             @OA\Property(property="code", type="integer", example="200"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/CourierScheduleTypeResource")
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     */
    public function index(): CourierScheduleTypesResource
    {
        $schedules = $this->scheduleService->getAllCourierScheduleTypes();

        return new CourierScheduleTypesResource($schedules);
    }
}
