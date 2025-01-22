<?php

declare(strict_types=1);

namespace App\Module\Status\Controllers;

use App\Http\Controllers\Controller;
use App\Module\Status\Contracts\Services\StatusTypeService;
use App\Module\Status\Requests\StatusTypeIndexRequest;
use App\Module\Status\Resources\StatusTypesResource;

class StatusTypeController extends Controller
{
    public function __construct(public StatusTypeService $service)
    {
    }

    /**
     * @OA\Get(
     *     path="/status-type",
     *     summary="Список тип статусов",
     *     operationId="getAllStatusTypesPaginated",
     *     tags={"Status Type"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__page"),
     *     @OA\Parameter (ref="#/components/parameters/__limit"),
     *     @OA\Parameter(
     *         name="typeId",
     *         in="query",
     *         required=false,
     *         description="Тип статус Айди",
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
     *                 @OA\Items(ref="#/components/schemas/StatusTypeResource")
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     */
    public function index(StatusTypeIndexRequest $request): StatusTypesResource
    {
        $statuses = $this->service->getAllStatusTypesPaginated($request->getDTO());

        return new StatusTypesResource($statuses);
    }
}
