<?php

declare(strict_types=1);

namespace App\Module\Delivery\Controllers;

use App\Http\Controllers\Controller;
use App\Module\Delivery\Contracts\Services\DeliveryService;
use App\Module\Delivery\Exports\DeliveryReportExport;
use App\Module\Delivery\Permissions\PermissionList;
use App\Module\Delivery\Requests\DeliveriesReportRequest;
use App\Module\Delivery\Requests\DeliveriesShowRequest;
use App\Module\Delivery\Resources\DeliveriesResource;
use Illuminate\Auth\Access\AuthorizationException;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class DeliveryController extends Controller
{
    public function __construct(
        private readonly DeliveryService $service
    ) {
    }

    /**
     * @OA\Get (
     *     path="/deliveries",
     *     tags={"Доставка"},
     *     summary="Список доставок для диспетчерской",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__limit"),
     *     @OA\Parameter (ref="#/components/parameters/__page"),
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
     *         name="containerId",
     *         in="query",
     *         description="Container Id",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=1,
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
     *     @OA\Parameter (
     *         name="waitListStatusIds",
     *         in="query",
     *         description="ID статусов листа ожидания",
     *         required=false,
     *         @OA\Schema(type="array",
     *             @OA\Items(
     *                 type="integer",
     *                 example="Статус накладной"
     *             )
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="invoiceNumber",
     *         in="query",
     *         required=false,
     *         description="Номер накладной",
     *         @OA\Schema(
     *             type="string",
     *             example="SP00012301",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="address",
     *         in="query",
     *         required=false,
     *         description="Адрес",
     *         @OA\Schema(
     *             type="string",
     *             example="Толеби 101",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="courierId",
     *         in="query",
     *         required=false,
     *         description="ID курьера",
     *         @OA\Schema(
     *             type="integer",
     *             example=1,
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="companyId",
     *         in="query",
     *         required=false,
     *         description="ID компании",
     *         @OA\Schema(
     *             type="integer",
     *             example=1,
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sectorId",
     *         in="query",
     *         required=false,
     *         description="ID сектора",
     *         @OA\Schema(
     *             type="integer",
     *             example=1,
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
     *                 ref="#/components/schemas/DeliveryResource",
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
    public function index(DeliveriesShowRequest $request): DeliveriesResource
    {
        $this->authorize(PermissionList::DELIVERY_INDEX);

        return new DeliveriesResource(
            $this->service->getAllPaginated($request->getDTO())
        );
    }

    /**
     * @OA\Get (
     *     path="/deliveries/report",
     *     tags={"Доставка"},
     *     summary="Отчет по доставкам",
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
     *     @OA\Parameter (
     *         name="waitListStatusIds",
     *         in="query",
     *         description="ID статусов листа ожидания",
     *         required=false,
     *         @OA\Schema(type="array",
     *             @OA\Items(
     *                 type="integer",
     *                 example="Статус накладной"
     *             )
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="invoiceNumber",
     *         in="query",
     *         required=false,
     *         description="Номер накладной",
     *         @OA\Schema(
     *             type="string",
     *             example="SP00012301",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="address",
     *         in="query",
     *         required=false,
     *         description="Адрес",
     *         @OA\Schema(
     *             type="string",
     *             example="Толеби 101",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="courierId",
     *         in="query",
     *         required=false,
     *         description="ID курьера",
     *         @OA\Schema(
     *             type="integer",
     *             example=1,
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="companyId",
     *         in="query",
     *         required=false,
     *         description="ID компании",
     *         @OA\Schema(
     *             type="integer",
     *             example=1,
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sectorId",
     *         in="query",
     *         required=false,
     *         description="ID сектора",
     *         @OA\Schema(
     *             type="integer",
     *             example=1,
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
     *             mediaType="text/csv",
     *             @OA\Schema(
     *             @OA\Property(
     *                 property="data",
     *                 ref="Symfony\Component\HttpFoundation\BinaryFileResponse"
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
     * @param DeliveriesReportRequest $request
     * @return BinaryFileResponse
     * @throws AuthorizationException
     */
    public function report(DeliveriesReportRequest $request): BinaryFileResponse
    {
        $this->authorize(PermissionList::DELIVERY_INDEX);

        $deliveries = $this->service->getForExport($request->getDTO());

        return Excel::download(
            new DeliveryReportExport($deliveries),
            'Доставки_с_' . $request->getDTO()->createdAtFrom . '_по_' . $request->getDTO()->createdAtTo . '.xlsx'
        );
    }
}
