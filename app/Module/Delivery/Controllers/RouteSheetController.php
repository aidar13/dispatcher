<?php

declare(strict_types=1);

namespace App\Module\Delivery\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\Delivery\Commands\CreateRouteSheetFrom1CCommand;
use App\Module\Delivery\Commands\SendRouteSheetToCabinetCommand;
use App\Module\Delivery\Contracts\Services\RouteSheetService;
use App\Module\Delivery\Exports\RouteSheetReportExport;
use App\Module\Delivery\Permissions\PermissionList;
use App\Module\Delivery\Requests\RouteSheetFromOneCRequest;
use App\Module\Delivery\Requests\RouteSheetIndexRequest;
use App\Module\Delivery\Resources\RouteSheetInfosResource;
use App\Module\Delivery\Resources\RouteSheetsResource;
use Illuminate\Auth\Access\AuthorizationException;
use Maatwebsite\Excel\Facades\Excel;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class RouteSheetController extends Controller
{
    public function __construct(
        private readonly RouteSheetService $service
    ) {
    }

    /**
     * @OA\Get (
     *     path="/route-sheet",
     *     tags={"Маршрутный лист"},
     *     operationId="routeSheetIndex",
     *     summary="Список маршрутных листов",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__limit"),
     *     @OA\Parameter (ref="#/components/parameters/__page"),
     *     @OA\Parameter(
     *         name="fromDate",
     *         in="path",
     *         required=false,
     *         description="Created at from",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="toDate",
     *         in="path",
     *         required=false,
     *         description="Created at until",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="courierId",
     *         in="path",
     *         required=false,
     *         description="Courier Id",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="invoiceNumber",
     *         in="path",
     *         required=false,
     *         description="Номер накладной",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="number",
     *         in="path",
     *         required=false,
     *         description="Номер выдачи (марш листа)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="cityId",
     *         in="path",
     *         required=false,
     *         description="Айди города",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="sectorId",
     *         in="path",
     *         required=false,
     *         description="Айди сектора",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="waveId",
     *         in="path",
     *         required=false,
     *         description="Айди волны",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="dispatcherSectorId",
     *         in="path",
     *         required=false,
     *         description="Айди диспетчер сектора",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/RouteSheetResource",
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
    public function index(RouteSheetIndexRequest $request): RouteSheetsResource
    {
        $this->authorize(PermissionList::ROUTE_SHEET_INDEX);

        return new RouteSheetsResource(
            $this->service->getAllPaginated($request->getDTO())
        );
    }

    /**
     * @OA\Get (
     *     path="/route-sheet/{id}",
     *     tags={"Маршрутный лист"},
     *     operationId="routeSheetShow",
     *     summary="Детальный просмотр марш листа",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *     @OA\Response(
     *         response=200,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/RouteSheetInfosResource",
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
    public function show(int $id): RouteSheetInfosResource
    {
        $this->authorize(PermissionList::ROUTE_SHEET_INDEX);

        return new RouteSheetInfosResource(
            $this->service->getWithInfosById($id)
        );
    }

    /**
     * @OA\Post (
     *     path="/one-c/route-sheet",
     *     summary="Создание марш листа из 1с",
     *     tags={"Маршрутный лист"},
     *     operationId="createRouteSheetFromOneC",
     *
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/RouteSheetFromOneCRequest")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Курьер успешно присвоен к маршрутному листу из 1с"),
     *              @OA\Property(property="data", type="object",example=null),
     *              @OA\Property(property="code", type="integer", example=200),
     *          ),
     *      description="",
     *      ),
     *      security={{
     *          "apiKey":{}
     *      }}
     * )
     *
     * @param RouteSheetFromOneCRequest $request
     * @return MessagesResource
     */
    public function createRouteSheetFromOneC(RouteSheetFromOneCRequest $request): MessagesResource
    {
        $this->dispatch(new CreateRouteSheetFrom1CCommand(
            $request->getDTO()
        ));

        return (new MessagesResource(null))
            ->setMessage('Курьер успешно присвоен к маршрутному листу из 1с');
    }

    /**
     * @OA\Get (
     *     path="/route-sheet/{id}/report",
     *     tags={"Маршрутный лист"},
     *     operationId="routeSheetReport",
     *     summary="Отчет по марш листу",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *     @OA\Response(
     *         response=200,
     *         @OA\MediaType(
     *             mediaType="text/csv",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="data",
     *                     ref="Symfony\Component\HttpFoundation\BinaryFileResponse"
     *                 )
     *            )
     *        ),
     *        description=""
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     *
     * @param int $id
     * @return BinaryFileResponse
     * @throws AuthorizationException
     */
    public function reportById(int $id): BinaryFileResponse
    {
        $this->authorize(PermissionList::ROUTE_SHEET_INDEX);

        $routeSheet = $this->service->getWithInfosById($id);

        return Excel::download(
            new RouteSheetReportExport($routeSheet),
            'Маршрутный лист ID: ' . $id . '.xlsx'
        );
    }

    /**
     * @OA\Post (
     *     path="/route-sheet/{id}/resend",
     *     summary="Переотправка марш листа из 1с",
     *     tags={"Маршрутный лист"},
     *     operationId="resendRouteSheetFromOneC",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Успешно переотправлено!"),
     *             @OA\Property(property="data", type="object",example=null),
     *             @OA\Property(property="code", type="integer", example=200),
     *         ),
     *      ),
     *      security={{
     *          "apiKey":{}
     *      }}
     * )
     *
     * @param int $id
     * @return MessagesResource
     */
    public function resend(int $id): MessagesResource
    {
        $this->authorize(PermissionList::ROUTE_SHEET_RESEND);

        $this->dispatchSync(new SendRouteSheetToCabinetCommand($id));

        return (new MessagesResource(null))
            ->setMessage('Успешно переотправлено!');
    }
}
