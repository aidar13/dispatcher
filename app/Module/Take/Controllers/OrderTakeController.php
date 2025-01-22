<?php

declare(strict_types=1);

namespace App\Module\Take\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\Take\Commands\AssignOrderTakesToCourierCommand;
use App\Module\Take\Commands\ChangeTakeDateByOrderIdCommand;
use App\Module\Take\Commands\SetStatusToTakeByInvoiceCommand;
use App\Module\Take\Contracts\Services\OrderTakeReportService;
use App\Module\Take\Contracts\Services\OrderTakeService;
use App\Module\Take\Exports\OrderTakesReportExport;
use App\Module\Take\Permissions\PermissionList;
use App\Module\Take\Requests\AssignTakeOrdersToCourierRequest;
use App\Module\Take\Requests\ChangeTakeDateByOrderIdRequest;
use App\Module\Take\Requests\OrderTakeShowRequest;
use App\Module\Take\Requests\SetStatusToTakeByInvoiceRequest;
use App\Module\Take\Resources\OrderTakesResource;
use App\Module\Take\Resources\OrderTakesShowResource;
use Illuminate\Auth\Access\AuthorizationException;
use Maatwebsite\Excel\Facades\Excel;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class OrderTakeController extends Controller
{
    public function __construct(
        private readonly OrderTakeService $service,
        private readonly OrderTakeReportService $reportService
    ) {
    }

    /**
     * @OA\Get (
     *     path="/order-take",
     *     tags={"Заборы"},
     *     summary="Список заборов для диспетчерской",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__limit"),
     *     @OA\Parameter (ref="#/components/parameters/__page"),
     *
     *     @OA\Parameter (
     *         name="dispatcherSectorId",
     *         in="query",
     *         description="Dispatcher Sector Id",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=1,
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="courierId",
     *         in="query",
     *         description="Courier ID",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=1,
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="companyId",
     *         in="query",
     *         description="Company ID",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=1,
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="cityId",
     *         in="query",
     *         description="City ID",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=1,
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="periodId",
     *         in="query",
     *         description="Period ID",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=1,
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="address",
     *         in="query",
     *         description="Адрес",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="Толеби",
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="orderNumber",
     *         in="query",
     *         description="Номер закзаза",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="001123",
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
     *     @OA\Parameter(
     *         name="createdAtFrom",
     *         in="query",
     *         required=false,
     *         description="Дата создания с",
     *         @OA\Schema(
     *             type="string",
     *             example="2023-01-01",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="createdAtTo",
     *         in="query",
     *         required=false,
     *         description="Дата создания до",
     *         @OA\Schema(
     *             type="string",
     *             example="2023-08-01",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="waitListStatusIds",
     *         in="query",
     *         required=false,
     *         description="ID статусов листов ожидания",
     *         @OA\Schema(type="array",
     *             @OA\Items(
     *                 type="integer",
     *                 example="1"
     *             )
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="hasPackType",
     *         in="query",
     *         required=false,
     *         description="Есть ли коробка у забора",
     *         @OA\Schema(
     *             type="boolean",
     *             example="true",
     *         )
     *     ),
     *     @OA\Parameter(
     *          name="incompletedAllTime",
     *          in="query",
     *          required=false,
     *          description="Незавершенные заборы за все время",
     *          @OA\Schema(
     *              type="boolean",
     *              example="true",
     *          )
     *      ),
     *     @OA\Parameter(
     *         name="waitListComment",
     *         in="query",
     *         required=false,
     *         description="Комментарий к листу ожидания",
     *         @OA\Schema(
     *             type="string",
     *             example="Заказ отменен",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/OrderTakeResource",
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
    public function index(OrderTakeShowRequest $request): OrderTakesResource
    {
        $this->authorize(PermissionList::ORDER_TAKE_INDEX);

        return new OrderTakesResource(
            $this->service->getAllPaginated($request->getDTO())
        );
    }

    /**
     * @OA\Post(
     *     path="/order-take/assign",
     *     tags={"Заборы"},
     *     summary="Назначить курьера на заказы(забор)",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema (
     *                 ref="#/components/schemas/AssignTakeOrdersToCourierRequest"
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Заборы успешно назначены на курьера",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Заборы успешно назначены на курьера"),
     *             @OA\Property(property="data", type="array", @OA\Items(), example="null"),
     *             @OA\Property(property="code", type="integer", example=200),
     *         )
     *     ),
     * )
     * @throws AuthorizationException
     */
    public function assignToCourier(AssignTakeOrdersToCourierRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::ORDER_TAKE_ASSIGN);

        $this->dispatch(new AssignOrderTakesToCourierCommand(
            $request->getDTO(),
            (int)auth()->id()
        ));

        return (new MessagesResource(null))
            ->setMessage('Заборы успешно назначены на курьера');
    }

    /**
     * @OA\Post(
     *     path="/order-take/change-date",
     *     tags={"Заборы"},
     *     summary="Изменить дату забора заказа",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema (
     *                 ref="#/components/schemas/ChangeTakeDateByOrderIdRequest"
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Дата забора успешно обновлена",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Дата забора успешно обновлена"),
     *             @OA\Property(property="data", type="array", @OA\Items(), example="null"),
     *             @OA\Property(property="code", type="integer", example=200),
     *         )
     *     ),
     * )
     * @throws AuthorizationException
     */
    public function changeTakeDateByOrderId(ChangeTakeDateByOrderIdRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::ORDER_TAKE_EDIT);

        $this->dispatch(new ChangeTakeDateByOrderIdCommand($request->getDTO()));

        return (new MessagesResource(null))
            ->setMessage('Дата забора успешно обновлена');
    }

    /**
     * @OA\Get (
     *     path="/order-take/report",
     *     tags={"Заборы"},
     *     summary="Выгрузка отчета по заборам",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (
     *         name="dispatcherSectorId",
     *         in="query",
     *         description="Dispatcher Sector Id",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=1,
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="courierId",
     *         in="query",
     *         description="Courier ID",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=1,
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="companyId",
     *         in="query",
     *         description="Company ID",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=1,
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="cityId",
     *         in="query",
     *         description="City ID",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=1,
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="periodId",
     *         in="query",
     *         description="Period ID",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=1,
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="address",
     *         in="query",
     *         description="Адрес",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="Толеби",
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="orderNumber",
     *         in="query",
     *         description="Номер закзаза",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="001123",
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
     *     @OA\Parameter(
     *         name="createdAtFrom",
     *         in="query",
     *         required=false,
     *         description="Дата создания с",
     *         @OA\Schema(
     *             type="string",
     *             example="2023-01-01",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="createdAtTo",
     *         in="query",
     *         required=false,
     *         description="Дата создания до",
     *         @OA\Schema(
     *             type="string",
     *             example="2023-08-01",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="waitListStatusIds",
     *         in="query",
     *         required=false,
     *         description="ID статусов листов ожидания",
     *         @OA\Schema(type="array",
     *             @OA\Items(
     *                 type="integer",
     *                 example="1"
     *             )
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="waitListComment",
     *         in="query",
     *         required=false,
     *         description="Комментарий к листу ожидания",
     *         @OA\Schema(
     *             type="string",
     *             example="Заказ отменен",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         @OA\MediaType(
     *             mediaType="text/csv",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="data",
     *                     ref="Symfony\Component\HttpFoundation\BinaryFileResponse"
     *                 )
     *             )
     *         ),
     *         description=""
     *     )
     * )
     *
     * @param OrderTakeShowRequest $request
     * @return BinaryFileResponse
     * @throws AuthorizationException
     */
    public function orderTakeReport(OrderTakeShowRequest $request): BinaryFileResponse
    {
        $this->authorize(PermissionList::ORDER_TAKE_REPORT);

        $orders = $this->reportService->getForExcel($request->getDTO());

        return Excel::download(
            new OrderTakesReportExport($orders),
            'Заборы_с_' . $request->getDTO()->dateFrom . '_по_' . $request->getDTO()->dateTo . '.xlsx'
        );
    }

    /**
     * @OA\Get(
     *    path="/order-take/{orderId}",
     *    summary="Карточка заказа",
     *    tags={"Заборы"},
     *    operationId="getOrderTakeDetailsByOrder",
     *    @OA\Parameter(
     *        name="orderId",
     *        in="query",
     *        description="ID заказа",
     *        required=true
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Карточка заказа",
     *        @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example=""),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/OrderTakesShowResource")),
     *             @OA\Property(property="code", type="integer", example=200),
     *         )
     *    ),
     * )
     * @throws AuthorizationException
     */
    public function takeInfoByOrderId(int $orderId): OrderTakesShowResource
    {
        $this->authorize(PermissionList::ORDER_TAKE_INDEX);

        return new OrderTakesShowResource(
            $this->service->getOrderWithTakes($orderId)
        );
    }

    /**
     * @OA\Put(
     *     path="/order-take/set-status-by-invoice",
     *     tags={"Заборы"},
     *     summary="Изменить статус забора",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema (
     *                 ref="#/components/schemas/SetStatusToTakeByInvoiceRequest"
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешно",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Успешно"),
     *             @OA\Property(property="data", type="array", @OA\Items(), example="null"),
     *             @OA\Property(property="code", type="integer", example=200),
     *         )
     *     ),
     * )
     * @throws AuthorizationException
     */
    public function setStatusByInvoice(SetStatusToTakeByInvoiceRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::ORDER_TAKE_SET_STATUS);

        $this->dispatch(new SetStatusToTakeByInvoiceCommand($request->getDTO()));

        return (new MessagesResource(null))->setMessage('Успешно');
    }
}
