<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\CourierApp\Commands\OrderTake\MassApproveTakesByInvoiceNumbersCommand;
use App\Module\CourierApp\Commands\OrderTake\SaveCourierShortcomingFilesCommand;
use App\Module\CourierApp\Commands\OrderTake\SaveInvoiceCargoPackCodeCommand;
use App\Module\CourierApp\Commands\OrderTake\SetOrderTakeInfoWaitListStatusCommand;
use App\Module\CourierApp\Contracts\Services\OrderTake\CourierOrderTakeService;
use App\Module\CourierApp\Permissions\PermissionList;
use App\Module\CourierApp\Requests\OrderTake\CourierOrderTakeShowRequest;
use App\Module\CourierApp\Requests\OrderTake\MassApproveOrderTakeRequest;
use App\Module\CourierApp\Requests\OrderTake\SaveInvoiceCargoPackCodeRequest;
use App\Module\CourierApp\Requests\OrderTake\SaveShortcomingFilesRequest;
use App\Module\CourierApp\Requests\WaitListStatus\SetWaitListStatusRequest;
use App\Module\CourierApp\Resources\OrderTake\CourierOrderTakesResource;
use App\Module\CourierApp\Resources\OrderTake\CourierOrderTakesShowResource;
use App\Module\CourierApp\Resources\ShortComingFiles\ShortComingFilesResource;
use App\Module\Order\Contracts\Services\OrderService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

final class OrderTakeController extends Controller
{
    public function __construct(
        private readonly CourierOrderTakeService $service,
        private readonly OrderService $orderService,
    ) {
    }

    /**
     * @OA\Get (
     *     path="/courier-app/order-take",
     *     tags={"Courier Takes"},
     *     operationId="getCourierTakes",
     *     summary="Список заборов для курьеров",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__limit"),
     *     @OA\Parameter (ref="#/components/parameters/__page"),
     *     @OA\Parameter (
     *         name="longitude",
     *         in="query",
     *         description="Долгота местоположения курьера",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="43.237163",
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="latitude",
     *         in="query",
     *         description="Широта местоположения курьера",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="76.945654",
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="dateFrom",
     *         in="query",
     *         description="Дата забора с",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="2022-12-24",
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="dateTo",
     *         in="query",
     *         description="Дата забора до",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="2022-12-24",
     *         )
     *     ),
     *     @OA\Parameter (
     *          name="takenAtFrom",
     *          in="query",
     *          description="Дата факт забора с",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              example="2022-12-24",
     *          )
     *      ),
     *      @OA\Parameter (
     *          name="takenAtTo",
     *          in="query",
     *          description="Дата факт забора до",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              example="2022-12-24",
     *          )
     *      ),
     *     @OA\Parameter (
     *         name="search",
     *         in="query",
     *         description="Поле для поиска по номеру закзаза, имени компании или по адресу",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="001123",
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="statusIds",
     *         in="query",
     *         description="ID статусов",
     *         required=false,
     *         @OA\Schema(type="array",
     *             @OA\Items(
     *                 type="integer",
     *                 example="Статус накладной"
     *             )
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="notInStatusIds",
     *         in="query",
     *         description="ID статусов которые не входят",
     *         required=false,
     *         @OA\Schema(type="array",
     *             @OA\Items(
     *                 type="integer",
     *                 example="Статус накладной"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="data",
     *                     ref="#/components/schemas/CourierOrderTakeResource",
     *                 )
     *             )
     *         ),
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @throws AuthorizationException
     */
    public function index(CourierOrderTakeShowRequest $request): CourierOrderTakesResource
    {
        $this->authorize(PermissionList::ORDER_TAKE_INDEX);

        return new CourierOrderTakesResource(
            $this->service->getAllPaginated($request->getDTO())
        );
    }


    /**
     * @OA\Get (
     *     path="/courier-app/order-take/{orderId}",
     *     tags={"Courier Takes"},
     *     operationId="getCourierTake",
     *     summary="Подробная ифнормация о заборах для курьеров",
     *
     *    @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *    @OA\Parameter(
     *        name="orderId",
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
     *                    ref="#/components/schemas/CourierTakeInvoiceResource"
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
     * @throws AuthorizationException
     */
    public function show(int $orderId): CourierOrderTakesShowResource
    {
        $this->authorize(PermissionList::ORDER_TAKE_INDEX);

        return new CourierOrderTakesShowResource(
            $this->service->getAllByOrderId($orderId)
        );
    }

    /**
     * @OA\Post(
     *     path="/courier-app/order-take/mass-approve",
     *     summary="Массовое подтверждение забора",
     *     operationId="massApproveTakes",
     *     tags={"Courier Takes"},
     *
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/MassApproveOrderTakeRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Заборы успешно подтверждены!"),
     *             @OA\Property(property="data", type="object", example=null),
     *             @OA\Property(property="code",type="integer", example=200)
     *         )
     *     ),
     *     security={{
     *         "bearer": {}
     *     }}
     * )
     * @param MassApproveOrderTakeRequest $request
     * @return MessagesResource
     * @throws AuthorizationException
     */
    public function massApproveOrderTakes(MassApproveOrderTakeRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::MASS_APPROVE_TAKES);

        $this->dispatch(new MassApproveTakesByInvoiceNumbersCommand(
            (int)Auth::id(),
            $request->getDTO(),
        ));

        return (new MessagesResource(null))
            ->setMessage('Заборы успешно подтверждены!');
    }

    /**
     * @OA\Post  (
     *     path="/courier-app/order-take/save-shortcoming-files",
     *     tags={"Courier Takes"},
     *     operationId="saveShortcomingReportFiles",
     *     summary="Загрузка акта об обнаружении недостатков",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/SaveShortcomingFilesRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Файлы успешно сохранены!"),
     *             @OA\Property(property="data", type="object",example=null),
     *             @OA\Property(property="code", type="integer", example="201"),
     *         )
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @param SaveShortcomingFilesRequest $request
     * @return MessagesResource
     * @throws AuthorizationException
     */
    public function saveShortcomingReportFiles(SaveShortcomingFilesRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::SAVE_SHORTCOMING_REPORT_FILES);

        $this->dispatch(new SaveCourierShortcomingFilesCommand((int)Auth::id(), $request->getDTO()));

        return (new MessagesResource(null))->setMessage('Файлы успешно сохранены!');
    }

    /**
     * @OA\Get (
     *     path="/courier-app/order-take/shortcoming-files/{orderId}",
     *     tags={"Courier Takes"},
     *     operationId="showShortcomingReportFiles",
     *     summary="Просмотр актов об обнаружении недостатков",
     *
     *     @OA\Parameter(
     *         name="orderId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="data",
     *                     ref="#/components/schemas/ShortComingFilesResource"
     *                 )
     *             )
     *         ),
     *         description=""
     *     ),
     *     security={{
     *         "bearer":{}
     *     }}
     * )
     *
     * @param int $orderId
     * @return ShortComingFilesResource
     * @throws AuthorizationException
     */
    public function showShortcomingReportFiles(int $orderId): ShortComingFilesResource
    {
        $this->authorize(PermissionList::SHOW_SHORTCOMING_REPORT_FILES);

        $order = $this->orderService->getById($orderId, ['id'], ['files']);

        return new ShortComingFilesResource($order);
    }

    /**
     * @OA\Put (
     *     path="/courier-app/order-take/{id}/set-wait-list-status",
     *     summary="Поставить статус листа ожидания",
     *     operationId="setWaitListStatusForOrderTake",
     *     tags={"Courier Takes"},
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema (
     *                 ref="#/components/schemas/SetWaitListStatusRequest"
     *             )
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Статус листа ожидание успешно присвоен!"),
     *             @OA\Property(property="data", type="object",example=null),
     *             @OA\Property(property="code", type="integer", example=200),
     *         )
     *     ),
     *     security={{
     *         "bearer": {}
     *     }}
     * )
     * @param int $id
     * @param SetWaitListStatusRequest $request
     * @return MessagesResource
     * @throws AuthorizationException
     */
    public function setWaitListStatus(int $id, SetWaitListStatusRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::ORDER_TAKE_SET_WAIT_LIST_STATUS);

        $this->dispatch(new SetOrderTakeInfoWaitListStatusCommand($id, $request->getDTO()));

        return (new MessagesResource(null))
            ->setMessage('Статус листа ожидание успешно присвоен!');
    }

    /**
     * @OA\Put(
     *     path="/courier-app/order-take/{invoiceId}/save-pack-code",
     *     summary="Поставить статус листа ожидания",
     *     operationId="savePackCode",
     *     tags={"Courier Takes"},
     *
     *     @OA\Parameter(
     *         name="invoiceId",
     *         description="invoiceId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/SaveInvoiceCargoPackCodeRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Штрихкод коробки успешно сохранен!"),
     *             @OA\Property(property="data", type="object", example=null),
     *             @OA\Property(property="code", type="integer", example=201),
     *         )
     *     ),
     *     security={{
     *         "bearer": {}
     *     }}
     * )
     * @param int $invoiceId
     * @param SaveInvoiceCargoPackCodeRequest $request
     * @return MessagesResource
     * @throws AuthorizationException
     */
    public function savePackCode(int $invoiceId, SaveInvoiceCargoPackCodeRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::ORDER_TAKE_SAVE_PACK_CODE);

        $this->dispatch(new SaveInvoiceCargoPackCodeCommand($invoiceId, $request->get('packCode')));

        return (new MessagesResource(null))->setMessage('Штрихкод коробки успешно сохранен!');
    }
}
