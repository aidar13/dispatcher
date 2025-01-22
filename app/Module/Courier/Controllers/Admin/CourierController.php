<?php

declare(strict_types=1);

namespace App\Module\Courier\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\Courier\Commands\UpdateCourierCommand;
use App\Module\Courier\Commands\UpdateCourierPhoneCommand;
use App\Module\Courier\Commands\UpdateCourierRoutingCommand;
use App\Module\Courier\Commands\UpLoadCourierFilesCommand;
use App\Module\Courier\Contracts\Services\CourierService;
use App\Module\Courier\Exports\CouriersExport;
use App\Module\Courier\Permissions\PermissionList;
use App\Module\Courier\Requests\CourierExportRequest;
use App\Module\Courier\Requests\CourierShowRequest;
use App\Module\Courier\Requests\CourierTakeListShowRequest;
use App\Module\Courier\Requests\UpdateCourierPhoneRequest;
use App\Module\Courier\Requests\UpdateCourierRequest;
use App\Module\Courier\Requests\UpdateCourierRoutingRequest;
use App\Module\Courier\Resources\CourierResource;
use App\Module\Courier\Resources\CouriersResource;
use App\Module\Courier\Resources\CouriersTakeListsResource;
use App\Module\File\Requests\UploadFilesRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CourierController extends Controller
{
    public function __construct(public CourierService $courierService)
    {
    }

    /**
     * @OA\Get(
     *     path="/couriers",
     *     summary="Список курьеров",
     *     operationId="getAllCouriers",
     *     tags={"Courier"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__limit"),
     *     @OA\Parameter (ref="#/components/parameters/__page"),
     *
     *     @OA\Parameter(
     *         name="dispatcherSectorIds",
     *         in="query",
     *         required=false,
     *         description="Dispatcher sector IDs",
     *         @OA\Schema(type="array", @OA\Items(type="integer", example=1))
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=false,
     *         description="Courier name",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="iin",
     *         in="query",
     *         required=false,
     *         description="Courier iin",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="phoneNumber",
     *         in="query",
     *         required=false,
     *         description="Courier phone number",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="companyId",
     *         in="query",
     *         required=false,
     *         description="Courier company",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="createdAtFrom",
     *         in="query",
     *         required=false,
     *         description="Created at from",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="createdAtUntil",
     *         in="query",
     *         required=false,
     *         description="Created at until",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="carNumber",
     *         in="query",
     *         required=false,
     *         description="Car number",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="carModel",
     *         in="query",
     *         required=false,
     *         description="Car model",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="statusIds",
     *         in="query",
     *         required=false,
     *         description="Status IDs of courier",
     *         @OA\Schema(type="array", @OA\Items(type="integer", example=1, description="Status ID"))
     *     ),
     *     @OA\Parameter(
     *         name="shiftId",
     *         in="query",
     *         required=false,
     *         description="Shift id 1=On shift/2=Out shift",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=false,
     *         description="id of a courier",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="code1C",
     *         in="query",
     *         required=false,
     *         description="code 1c of a courier",
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
     *                 @OA\Items(ref="#/components/schemas/CourierItemResource")
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     * @throws AuthorizationException
     */
    public function index(CourierShowRequest $request): CouriersResource
    {
        $this->authorize(PermissionList::COURIER_INDEX);

        $couriers = $this->courierService->getAllPaginated($request->getDTO());

        return new CouriersResource($couriers);
    }

    /**
     * @OA\Get(
     *     path="/couriers/{id}",
     *     summary="Получение курьера по ID",
     *     operationId="getCourierById",
     *     tags={"Courier"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example=null),
     *             @OA\Property(property="code", type="integer", example="200"),
     *             @OA\Property(ref="#/components/schemas/CourierResource")
     *         )
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     * @throws AuthorizationException
     */
    public function show(int $id): CourierResource
    {
        $this->authorize(PermissionList::COURIER_INDEX);

        $couriers = $this->courierService->getCourierById($id);

        return new CourierResource($couriers);
    }

    /**
     * @OA\Put(
     *     path="/couriers/{id}",
     *     summary="Редактирование курьера",
     *     operationId="updateCourier",
     *     tags={"Courier"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/UpdateCourierRequest")
     *          )
     *     ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Успешный ответ",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Изменения успешно сохранены"),
     *              @OA\Property(property="code", type="integer", example="200"),
     *          )
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     * @throws AuthorizationException
     */
    public function update(int $id, UpdateCourierRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::COURIER_UPDATE);

        $this->dispatch(new UpdateCourierCommand(
            $id,
            $request->getDTO()
        ));

        return (new MessagesResource(null))
            ->setMessage('Изменения успешно сохранены');
    }

    /**
     * @OA\Get(
     *     path="/couriers/take-list",
     *     summary="Список курьеров на забор",
     *     operationId="getCouriersList",
     *     tags={"Courier"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__limit"),
     *     @OA\Parameter (ref="#/components/parameters/__page"),
     *
     *     @OA\Parameter(
     *         name="statusIds",
     *         in="query",
     *         required=false,
     *         description="Status IDs of couriers",
     *         @OA\Schema(type="array", @OA\Items(type="integer", example=1, description="Status ID"))
     *     ),
     *     @OA\Parameter(
     *         name="dispatcherSectorId",
     *         in="query",
     *         required=false,
     *         description="Dispatcher sector ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="scheduleTypeId",
     *         in="query",
     *         required=false,
     *         description="Schedule Type ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="sectorIds",
     *         in="query",
     *         required=false,
     *         description="Status IDs of sectors",
     *         @OA\Schema(type="array", @OA\Items(type="integer", example=1, description="Status ID"))
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
     *                 @OA\Items(ref="#/components/schemas/CourierTakeListResource")
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     * @throws AuthorizationException
     */
    public function takeList(CourierTakeListShowRequest $request): CouriersTakeListsResource
    {
        $this->authorize(PermissionList::COURIER_INDEX);

        $couriers = $this->courierService->getCouriersTakeListPaginated($request->getDTO());

        return new CouriersTakeListsResource($couriers);
    }

    /**
     * @OA\Get(
     *     path="/couriers/export",
     *     summary="Экспорт курьеров",
     *     operationId="getCouriersExport",
     *     tags={"Courier"},
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\Parameter(
     *         name="dispatcherSectorIds",
     *         in="query",
     *         required=false,
     *         description="Dispatcher sector IDs",
     *         @OA\Schema(type="array", @OA\Items(type="integer", example=1))
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=false,
     *         description="Courier name",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="iin",
     *         in="query",
     *         required=false,
     *         description="Courier iin",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="phoneNumber",
     *         in="query",
     *         required=false,
     *         description="Courier phone number",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="companyId",
     *         in="query",
     *         required=false,
     *         description="Courier company",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="fromDate",
     *         in="query",
     *         required=false,
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
     *         name="carNumber",
     *         in="query",
     *         required=false,
     *         description="Car number",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="carModel",
     *         in="query",
     *         required=false,
     *         description="Car model",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="statusIds",
     *         in="query",
     *         required=false,
     *         description="Status IDs of courier",
     *         @OA\Schema(type="array", @OA\Items(type="integer", example=1, description="Status ID"))
     *     ),
     *     @OA\Parameter(
     *         name="shiftId",
     *         in="query",
     *         required=false,
     *         description="Shift id 1=On shift/2=Out shift",
     *         @OA\Schema(type="integer")
     *     ),
     *    @OA\Response(
     *        response=200,
     *        @OA\MediaType(
     *            mediaType="text/csv",
     *        @OA\Schema(
     *            @OA\Property(
     *                property="data",
     *                ref="Symfony\Component\HttpFoundation\BinaryFileResponse"
     *            )
     *        )
     *    ),
     *    description="Успешно!"
     *    )
     * )
     * @param CourierExportRequest $request
     * @return BinaryFileResponse
     * @throws AuthorizationException
     */
    public function export(CourierExportRequest $request): BinaryFileResponse
    {
        $this->authorize(PermissionList::COURIER_EXPORT);

        return Excel::download(
            new CouriersExport($request->getDTO()),
            'couriers-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * @OA\Post(
     *     path="/couriers/{id}/upload-document",
     *     summary="Загрузить файл курьера",
     *     operationId="courierUploadDocument",
     *     tags={"Courier"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema (
     *                 ref="#/components/schemas/UploadFilesRequest"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Файл успешно загружен"),
     *             @OA\Property(property="data", type="object",example=null),
     *             @OA\Property(property="code", type="integer", example=200),
     *         ),
     *         description="",
     *    ),
     * )
     * @throws AuthorizationException
     */
    public function uploadDocument(int $courierId, UploadFilesRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::COURIER_UPDATE);

        $this->dispatch(new UpLoadCourierFilesCommand(
            (int)Auth::id(),
            $courierId,
            $request->getDTO(),
        ));

        return (new MessagesResource(null))
            ->setMessage('Файл успешно загружен');
    }

    /**
     * @OA\Put(
     *     path="/couriers/{id}/set-phone",
     *     summary="Редактирование номер телефона курьера",
     *     operationId="updateCourierPhone",
     *     tags={"Courier"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/UpdateCourierPhoneRequest")
     *          )
     *     ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Успешный ответ",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Номер телефона успешно изменен!"),
     *              @OA\Property(property="code", type="integer", example="200"),
     *          )
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     * @throws AuthorizationException
     */
    public function updatePhoneNumber(int $courierId, UpdateCourierPhoneRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::COURIER_UPDATE);

        $this->dispatch(new UpdateCourierPhoneCommand(
            (int)Auth::id(),
            $courierId,
            $request->get('phoneNumber'),
        ));

        return (new MessagesResource(null))
            ->setMessage('Номер телефона успешно изменен!');
    }

    /**
     * @OA\Put(
     *     path="/couriers/{id}/routing",
     *     summary="Вкл/выкл статус яндекс маршрутизации курьера",
     *     operationId="updateCourierRouting",
     *     tags={"Courier"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/UpdateCourierRoutingRequest")
     *          )
     *     ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Успешный ответ",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Успешно изменен!"),
     *              @OA\Property(property="code", type="integer", example="200"),
     *          )
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     * @throws AuthorizationException
     */
    public function updateRouting(UpdateCourierRoutingRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::COURIER_UPDATE);

        $this->dispatch(new UpdateCourierRoutingCommand(
            (int)$request->get('courierId'),
            (bool)$request->get('routingEnabled')
        ));

        return (new MessagesResource(null))
            ->setMessage('Успешно изменен!');
    }
}
