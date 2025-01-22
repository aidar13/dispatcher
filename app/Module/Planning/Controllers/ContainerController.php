<?php

declare(strict_types=1);

namespace App\Module\Planning\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\Order\Commands\CreateFastDeliveryOrderByContainerCommand;
use App\Module\Order\Contracts\Services\InvoiceService;
use App\Module\Planning\Commands\AssignCourierToContainersCommand;
use App\Module\Planning\Commands\AttachInvoicesToContainerCommand;
use App\Module\Planning\Commands\ChangeContainerStatusCommand;
use App\Module\Planning\Commands\CreateContainerCommand;
use App\Module\Planning\Commands\DeleteContainerCommand;
use App\Module\Planning\Commands\GenerateWaveContainersCommand;
use App\Module\Planning\Commands\SendContainersToAssemblyCommand;
use App\Module\Planning\Contracts\Services\ContainerService;
use App\Module\Planning\Exports\ContainersExport;
use App\Module\Planning\Permissions\PermissionList;
use App\Module\Planning\Requests\AssignCourierToContainerRequest;
use App\Module\Planning\Requests\AttachInvoicesToContainerRequest;
use App\Module\Planning\Requests\ChangeContainerStatusRequest;
use App\Module\Planning\Requests\ContainerShowRequest;
use App\Module\Planning\Requests\CreateContainerRequest;
use App\Module\Planning\Requests\GenerateContainerRequest;
use App\Module\Planning\Requests\SendToAssemblyRequest;
use App\Module\Planning\Resources\ContainerInvoicesResource;
use App\Module\Planning\Resources\ContainersResource;
use Illuminate\Auth\Access\AuthorizationException;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class ContainerController extends Controller
{
    public function __construct(
        private readonly ContainerService $service,
        private readonly InvoiceService $invoiceService,
    ) {
    }

    /**
     * @OA\Get (
     *     path="/containers",
     *     operationId="getContiners",
     *     tags={"Container"},
     *     summary="Список всех контейнеров без пагинации по фильтрам",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\Parameter (
     *         name="invoiceNumber",
     *         in="query",
     *         description="Номер наладной",
     *         required=false,
     *         @OA\Schema(type="string",example="SP500")
     *     ),
     *     @OA\Parameter (
     *         name="deliveryStatusIds",
     *         in="query",
     *         description="Статусы доставки",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="integer", example=1))
     *     ),
     *     @OA\Parameter (
     *         name="userId",
     *         in="query",
     *         description="Id создателя контейнера",
     *         required=false,
     *         @OA\Schema(type="ineger",example="36")
     *     ),
     *     @OA\Parameter (
     *         name="courierId",
     *         in="query",
     *         description="Id курьера",
     *         required=false,
     *         @OA\Schema(type="ineger",example="36")
     *     ),
     *     @OA\Parameter (
     *         name="title",
     *         in="query",
     *         description="Название контейнера",
     *         required=false,
     *         @OA\Schema(type="string",example="МГГ-500")
     *     ),
     *     @OA\Parameter (
     *         name="sectorId",
     *         in="query",
     *         description="Название контейнера",
     *         required=false,
     *         @OA\Schema(type="string",example="МГГ-500")
     *     ),
     *     @OA\Parameter (
     *         name="waveId",
     *         in="query",
     *         description="ID волны",
     *         required=false,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Parameter (
     *         name="sectorIds",
     *         in="query",
     *         description="ID секторов",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="integer", example=1))
     *     ),
     *     @OA\Parameter (
     *         name="statusIds",
     *         in="query",
     *         description="ID статусов",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="integer", example=1))
     *     ),
     *     @OA\Parameter (
     *         name="statusId",
     *         in="query",
     *         description="ID статуса",
     *         required=false,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Parameter (
     *         name="cargoType",
     *         in="query",
     *         description="Тип груза (1 если мологабарит, 2 если крупногабаритный)",
     *         required=false,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Parameter (
     *         name="date",
     *         in="query",
     *         description="Дата",
     *         required=false,
     *         @OA\Schema(type="string",example="2023-08-18")
     *     ),
     *     @OA\Parameter (
     *         name="dateFrom",
     *         in="query",
     *         description="Дата с",
     *         required=false,
     *         @OA\Schema(type="string",example="2023-08-18")
     *     ),
     *     @OA\Parameter (
     *         name="dateTo",
     *         in="query",
     *         description="Дата по",
     *         required=false,
     *         @OA\Schema(type="string",example="2023-08-18")
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
     *                     ref="#/components/schemas/ContainerItemResource",
     *                 )
     *             )
     *         ),
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @param ContainerShowRequest $request
     * @return ContainersResource
     * @throws AuthorizationException
     */
    public function index(ContainerShowRequest $request): ContainersResource
    {
        $this->authorize(PermissionList::CONTAINER_INDEX);

        return new ContainersResource(
            $this->service->getAllContainers($request->getDTO())
        );
    }

    /**
     * @OA\Get (
     *     path="/containers/paginated",
     *     operationId="getContinersPaginated",
     *     tags={"Container"},
     *     summary="Список всех контейнеров c пагинацией по фильтрам",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\Parameter (
     *         name="invoiceNumber",
     *         in="query",
     *         description="Номер наладной",
     *         required=false,
     *         @OA\Schema(type="string",example="SP500")
     *     ),
     *     @OA\Parameter (
     *         name="deliveryStatusIds",
     *         in="query",
     *         description="Статусы доставки",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="integer", example=1))
     *     ),
     *     @OA\Parameter (
     *         name="userId",
     *         in="query",
     *         description="Id создателя контейнера",
     *         required=false,
     *         @OA\Schema(type="ineger",example="36")
     *     ),
     *     @OA\Parameter (
     *         name="courierId",
     *         in="query",
     *         description="Id курьера",
     *         required=false,
     *         @OA\Schema(type="ineger",example="36")
     *     ),
     *     @OA\Parameter (
     *         name="title",
     *         in="query",
     *         description="Название контейнера",
     *         required=false,
     *         @OA\Schema(type="string",example="МГГ-500")
     *     ),
     *     @OA\Parameter (
     *         name="sectorId",
     *         in="query",
     *         description="Название контейнера",
     *         required=false,
     *         @OA\Schema(type="string",example="МГГ-500")
     *     ),
     *     @OA\Parameter (
     *         name="waveId",
     *         in="query",
     *         description="ID волны",
     *         required=false,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Parameter (
     *         name="sectorIds",
     *         in="query",
     *         description="ID секторов",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="integer", example=1))
     *     ),
     *     @OA\Parameter (
     *         name="statusIds",
     *         in="query",
     *         description="ID статусов",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="integer", example=1))
     *     ),
     *     @OA\Parameter (
     *         name="statusId",
     *         in="query",
     *         description="ID статуса",
     *         required=false,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Parameter (
     *         name="cargoType",
     *         in="query",
     *         description="Тип груза (1 если мологабарит, 2 если крупногабаритный)",
     *         required=false,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Parameter (
     *         name="date",
     *         in="query",
     *         description="Дата",
     *         required=false,
     *         @OA\Schema(type="string",example="2023-08-18")
     *     ),
     *     @OA\Parameter (
     *         name="dateFrom",
     *         in="query",
     *         description="Дата с",
     *         required=false,
     *         @OA\Schema(type="string",example="2023-08-18")
     *     ),
     *     @OA\Parameter (
     *         name="dateTo",
     *         in="query",
     *         description="Дата по",
     *         required=false,
     *         @OA\Schema(type="string",example="2023-08-18")
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
     *                     ref="#/components/schemas/ContainerItemResource",
     *                 )
     *             )
     *         ),
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @param ContainerShowRequest $request
     * @return ContainersResource
     * @throws AuthorizationException
     */
    public function paginated(ContainerShowRequest $request): ContainersResource
    {
        $this->authorize(PermissionList::CONTAINER_INDEX);

        return new ContainersResource(
            $this->service->getContainersPaginated($request->getPaginatedDTO())
        );
    }

    /**
     * @OA\Get (
     *     path="/containers/invoice/{invoiceId}",
     *     operationId="getContinerInvoice",
     *     tags={"Container"},
     *     summary="Модалка накладной на стр контейнеров",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\Parameter (
     *         name="invoiceId",
     *         in="query",
     *         description="Номер наладной",
     *         required=true,
     *         @OA\Schema(type="integer",example="500")
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
     *                     ref="#/components/schemas/ContainerInvoicesResource",
     *                 )
     *             )
     *         ),
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @param int $invoiceId
     * @return ContainerInvoicesResource
     * @throws AuthorizationException
     */
    public function invoice(int $invoiceId): ContainerInvoicesResource
    {
        $this->authorize(PermissionList::CONTAINER_INDEX);

        return new ContainerInvoicesResource($this->invoiceService->getById($invoiceId));
    }

    /**
     * @OA\Get (
     *     path="/containers/export",
     *     operationId="getContinersExport",
     *     tags={"Container"},
     *     summary="Список всех контейнеров по фильтрам",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\Parameter (
     *         name="invoiceNumber",
     *         in="query",
     *         description="Номер наладной",
     *         required=false,
     *         @OA\Schema(type="string",example="SP500")
     *     ),
     *     @OA\Parameter (
     *         name="deliveryStatusIds",
     *         in="query",
     *         description="Статусы доставки",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="integer", example=1))
     *     ),
     *     @OA\Parameter (
     *         name="userId",
     *         in="query",
     *         description="Id создателя контейнера",
     *         required=false,
     *         @OA\Schema(type="ineger",example="36")
     *     ),
     *     @OA\Parameter (
     *         name="courierId",
     *         in="query",
     *         description="Id курьера",
     *         required=false,
     *         @OA\Schema(type="ineger",example="36")
     *     ),
     *     @OA\Parameter (
     *         name="title",
     *         in="query",
     *         description="Название контейнера",
     *         required=false,
     *         @OA\Schema(type="string",example="МГГ-500")
     *     ),
     *     @OA\Parameter (
     *         name="sectorId",
     *         in="query",
     *         description="Название контейнера",
     *         required=false,
     *         @OA\Schema(type="string",example="МГГ-500")
     *     ),
     *     @OA\Parameter (
     *         name="waveId",
     *         in="query",
     *         description="ID волны",
     *         required=false,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Parameter (
     *         name="sectorIds",
     *         in="query",
     *         description="ID секторов",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="integer", example=1))
     *     ),
     *     @OA\Parameter (
     *         name="statusIds",
     *         in="query",
     *         description="ID статусов",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="integer", example=1))
     *     ),
     *     @OA\Parameter (
     *         name="statusId",
     *         in="query",
     *         description="ID статуса",
     *         required=false,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Parameter (
     *         name="cargoType",
     *         in="query",
     *         description="Тип груза (1 если мологабарит, 2 если крупногабаритный)",
     *         required=false,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Parameter (
     *         name="date",
     *         in="query",
     *         description="Дата",
     *         required=false,
     *         @OA\Schema(type="string",example="2023-08-18")
     *     ),
     *     @OA\Parameter (
     *         name="dateFrom",
     *         in="query",
     *         description="Дата с",
     *         required=false,
     *         @OA\Schema(type="string",example="2023-08-18")
     *     ),
     *     @OA\Parameter (
     *         name="dateTo",
     *         in="query",
     *         description="Дата по",
     *         required=false,
     *         @OA\Schema(type="string",example="2023-08-18")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\MediaType(
     *             mediaType="text/csv",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="data",
     *                     ref="Symfony\Component\HttpFoundation\BinaryFileResponse"
     *                 )
     *             )
     *         ),
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @param ContainerShowRequest $request
     * @return BinaryFileResponse
     * @throws AuthorizationException
     */
    public function export(ContainerShowRequest $request): BinaryFileResponse
    {
        $this->authorize(PermissionList::CONTAINER_INDEX);

        return Excel::download(
            new ContainersExport($this->service->getAllContainers($request->getDTO())),
            'Контейнеры-' . date('Y-m-d H:i:s') . '.xlsx'
        );
    }

    /**
     * @OA\Post (
     *     path="/containers/generate",
     *     operationId="generateContiner",
     *     tags={"Container"},
     *     summary="Сформировать контейнеры",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\Parameter (
     *         name="dispatcherSectorId",
     *         in="query",
     *         description="ID диспетчер сектора",
     *         required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Parameter (
     *         name="waveId",
     *         in="query",
     *         description="ID волны",
     *         required=true,
     *         @OA\Schema(type="integer",example="1")
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
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Parameter (
     *         name="date",
     *         in="query",
     *         description="Дата планирование",
     *         required=true,
     *         @OA\Schema(type="string",example="2023-08-18")
     *     ),
     *
     *     @OA\Response(
     *        response=200,
     *        description="Контейнеры сформированы!",
     *        @OA\JsonContent(
     *            @OA\Property(property="success", type="boolean", example=true),
     *            @OA\Property(property="message", type="string", example="Контейнеры сформированы!"),
     *            @OA\Property(property="data", type="object",example=null),
     *            @OA\Property(property="code", type="integer", example=200),
     *        ),
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @param GenerateContainerRequest $request
     * @return MessagesResource
     * @throws AuthorizationException
     */
    public function generate(GenerateContainerRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::CONTAINER_GENERATE);

        $this->dispatchSync(new GenerateWaveContainersCommand(
            (int)auth()->id(),
            $request->getDTO()
        ));

        return (new MessagesResource(null))
            ->setMessage('Контейнеры сформированы!');
    }

    /**
     * @OA\Post (
     *     path="/containers",
     *     operationId="storeContainer",
     *     tags={"Container"},
     *     summary="Создать контейнер",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CreateContainerRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Контейнер создан!"),
     *             @OA\Property(property="data", type="object",example=null),
     *             @OA\Property(property="code", type="integer", example=201),
     *         ),
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @param CreateContainerRequest $request
     * @return MessagesResource
     * @throws AuthorizationException
     */
    public function store(CreateContainerRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::CONTAINER_CREATE);

        $this->dispatchSync(new CreateContainerCommand(
            (int)auth()->id(),
            $request->getDTO()
        ));

        return (new MessagesResource(null))
            ->setMessage('Контейнер создан!');
    }

    /**
     * @OA\Post (
     *     path="/containers/{containerId}/attach-invoices",
     *     operationId="attachInvoicesToContainer",
     *     tags={"Container"},
     *     summary="Прокинуть накладные в контейнер",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\Parameter (
     *         name="containerId",
     *         in="path",
     *         description="ID контейнера",
     *         required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AttachInvoicesToContainerRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Контейнер создан!"),
     *             @OA\Property(property="data", type="object",example=null),
     *             @OA\Property(property="code", type="integer", example=201),
     *         ),
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @param AttachInvoicesToContainerRequest $request
     * @return MessagesResource
     * @throws AuthorizationException
     */
    public function attachInvoices(AttachInvoicesToContainerRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::CONTAINER_ATTACH_INVOICE);

        $this->dispatch(new AttachInvoicesToContainerCommand(
            $request->getDTO()
        ));

        return (new MessagesResource(null))
            ->setMessage('Накладные перенесены в контейнер!');
    }

    /**
     * @OA\Post (
     *     path="/containers/change-status",
     *     operationId="changeStatusOfContainers",
     *     tags={"Container"},
     *     summary="Смена статуса контейнера и нкалдных в контейнере",
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ChangeContainerStatusRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Статус контейнера был изменен!"),
     *             @OA\Property(property="data", type="object",example=null),
     *             @OA\Property(property="code", type="integer", example=201),
     *         ),
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @param ChangeContainerStatusRequest $request
     * @return MessagesResource
     */
    public function changeStatus(ChangeContainerStatusRequest $request): MessagesResource
    {
        $this->dispatch(new ChangeContainerStatusCommand(
            $request->getDTO()
        ));

        return (new MessagesResource(null))
            ->setMessage('Статус контейнера был изменен!');
    }

    /**
     * @OA\Post (
     *     path="/containers/assign-courier",
     *     operationId="assignCourierToContainer",
     *     tags={"Container"},
     *     summary="Назначить курьера на контейнер",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AssignCourierToContainerRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Курьер успешно назначен!"),
     *             @OA\Property(property="data", type="object",example=null),
     *             @OA\Property(property="code", type="integer", example=201),
     *         ),
     *     description="",
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @param AssignCourierToContainerRequest $request
     * @return MessagesResource
     * @throws AuthorizationException
     */
    public function assignCourier(AssignCourierToContainerRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::CONTAINER_ASSIGN_COURIER);

        $this->dispatch(new AssignCourierToContainersCommand(
            $request->getDTO()
        ));

        return (new MessagesResource(null))
            ->setMessage('Курьер успешно назначен!');
    }

    /**
     * @OA\Post (
     *     path="/containers/send-assembly",
     *     operationId="sendContainersToAssembly",
     *     tags={"Container"},
     *     summary="отправить контейнеров на сборку",
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/SendToAssemblyRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Контейнеры успешно отправлены на сборку!"),
     *             @OA\Property(property="data", type="object",example=null),
     *             @OA\Property(property="code", type="integer", example=201),
     *         ),
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @param SendToAssemblyRequest $request
     * @return MessagesResource
     * @throws AuthorizationException
     */
    public function sendToAssembly(SendToAssemblyRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::CONTAINER_SEND_TO_ASSEMBLY);

        $this->dispatch(new SendContainersToAssemblyCommand(
            $request->getDTO()
        ));

        return (new MessagesResource(null))
            ->setMessage('Контейнеры успешно отправлены на сборку!');
    }

    /**
     * @OA\Delete (
     *     path="/containers/{containerId}",
     *     operationId="destroyContainer",
     *     tags={"Container"},
     *     summary="Удалить контейнер",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\Parameter (
     *         name="containerId",
     *         in="path",
     *         description="ID контейнера",
     *         required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Контейнер удален!"),
     *             @OA\Property(property="data", type="object",example=null),
     *             @OA\Property(property="code", type="integer", example=201),
     *         ),
     *         description="",
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @param int $containerId
     * @return MessagesResource
     * @throws AuthorizationException
     */
    public function destroy(int $containerId): MessagesResource
    {
        $this->authorize(PermissionList::CONTAINER_DESTROY);

        $this->dispatch(new DeleteContainerCommand($containerId));

        return (new MessagesResource(null))
            ->setMessage('Контейнер удален!');
    }

    /**
     * @OA\Post (
     *     path="/containers/{containerId}/resend",
     *     operationId="resendContainer",
     *     tags={"Container"},
     *     summary="Переотправить контейнер",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\Parameter (
     *         name="containerId",
     *         in="path",
     *         description="ID контейнера",
     *         required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Успешно переотправлен!"),
     *             @OA\Property(property="data", type="object",example=null),
     *             @OA\Property(property="code", type="integer", example=201),
     *         ),
     *         description="",
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @param int $containerId
     * @return MessagesResource
     */
    public function resendContainer(int $containerId): MessagesResource
    {
        dispatch_sync(new CreateFastDeliveryOrderByContainerCommand($containerId));

        return (new MessagesResource(null))
            ->setMessage('Успешно переотправлен!');
    }
}
