<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\CourierApp\Commands\Delivery\ApproveDeliveryByInvoiceIdCommand;
use App\Module\CourierApp\Commands\Delivery\ApproveDeliveryCommand;
use App\Module\CourierApp\Commands\Delivery\SetDeliveryInfoWaitListStatusCommand;
use App\Module\CourierApp\Contracts\Services\Delivery\CourierDeliveryService;
use App\Module\CourierApp\Permissions\PermissionList;
use App\Module\CourierApp\Requests\Delivery\ApproveDeliveryRequest;
use App\Module\CourierApp\Requests\Delivery\ApproveDeliveryViaVerificationRequest;
use App\Module\CourierApp\Requests\Delivery\CourierDeliveryShowRequest;
use App\Module\CourierApp\Requests\WaitListStatus\SetWaitListStatusRequest;
use App\Module\CourierApp\Resources\Delivery\CourierDeliveriesResource;
use App\Module\CourierApp\Resources\Delivery\CourierDeliveryShowResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

final class DeliveryController extends Controller
{
    public function __construct(
        private readonly CourierDeliveryService $service
    ) {
    }

    /**
     * @OA\Get (
     *     path="/courier-app/delivery",
     *     tags={"Courier Deliveries"},
     *     operationId="getCourierDeliveries",
     *     summary="Список доставок для курьеров",
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
     *         name="createdAtFrom",
     *         in="query",
     *         description="Дата создания с",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="2022-12-24",
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="createdAtTo",
     *         in="query",
     *         description="Дата создания до",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="2022-12-24",
     *         )
     *     ),
     *    @OA\Parameter (
     *         name="deliveredAtFrom",
     *         in="query",
     *         description="Дата доставки с",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="2022-12-24",
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="deliveredAtTo",
     *         in="query",
     *         description="Дата доставки до",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="2022-12-24",
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="deliveredAtFrom",
     *         in="query",
     *         description="Дата доставки с",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="2022-12-24",
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="deliveredAtTo",
     *         in="query",
     *         description="Дата доставки до",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="2022-12-24",
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="search",
     *         in="query",
     *         description="Поле для поиска по номеру накладной, имени компании или по адресу",
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
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/CourierDeliveryResource",
     *             )
     *         )
     *     ),
     *         description=""
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @param CourierDeliveryShowRequest $request
     * @return CourierDeliveriesResource
     * @throws AuthorizationException
     */
    public function index(CourierDeliveryShowRequest $request): CourierDeliveriesResource
    {
        $this->authorize(PermissionList::DELIVERY_INDEX);

        return new CourierDeliveriesResource(
            $this->service->getAllPaginated($request->getDTO())
        );
    }

    /**
     * @OA\Get (
     *     path="/courier-app/delivery/{id}",
     *     tags={"Courier Deliveries"},
     *     operationId="getCourierDeliveryShow",
     *     summary="Подробный просмотр доставки для курьеров",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__id"),
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
     *     @OA\Response(
     *         response=200,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/CourierDeliveryShowResource",
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
     * @param int $id
     * @return CourierDeliveryShowResource
     * @throws AuthorizationException
     */
    public function show(int $id): CourierDeliveryShowResource
    {
        $this->authorize(PermissionList::DELIVERY_INDEX);

        return new CourierDeliveryShowResource(
            $this->service->getById($id)
        );
    }

    /**
     * @OA\Post(
     *     path="/courier-app/delivery/{id}/approve",
     *     description="Подтверждение доставки",
     *     operationId="approveDelivery",
     *     tags={"Courier Deliveries"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/ApproveDeliveryRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="success", type="boolean", example=true),
     *                 @OA\Property(property="message", type="string", example="Доставка подтверждена!"),
     *                 @OA\Property(property="data", type="object", example=null),
     *                 @OA\Property(property="code",type="integer", example=201)
     *             )
     *         )
     *     ),
     *     security={{
     *         "bearer": {}
     *     }}
     * )
     * @param int $id
     * @param ApproveDeliveryRequest $request
     * @return MessagesResource
     * @throws AuthorizationException
     */
    public function approve(int $id, ApproveDeliveryRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::DELIVERY_APPROVE);

        $this->dispatch(new ApproveDeliveryCommand(
            $id,
            $request->getDTO(),
            (int)Auth::id(),
        ));

        return (new MessagesResource(null))
            ->setMessage('Доставка подтверждена!');
    }

    /**
     * @OA\Post(
     *     path="/courier-app/delivery/approve-via-verification",
     *     description="Подтверждение доставки после верификации",
     *     operationId="approveDeliveryViaVerification",
     *     tags={"Courier Deliveries"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/ApproveDeliveryViaVerificationRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="success", type="boolean", example=true),
     *                 @OA\Property(property="message", type="string", example="Доставка подтверждена!"),
     *                 @OA\Property(property="data", type="object", example=null),
     *                 @OA\Property(property="code",type="integer", example=201)
     *             )
     *         )
     *     ),
     *     security={{
     *         "bearer": {}
     *     }}
     * )
     * @param ApproveDeliveryViaVerificationRequest $request
     * @return MessagesResource
     * @throws AuthorizationException
     */
    public function approveViaVerification(ApproveDeliveryViaVerificationRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::DELIVERY_APPROVE);

        $this->dispatch(new ApproveDeliveryByInvoiceIdCommand($request->getDTO()));

        return (new MessagesResource(null))
            ->setMessage('Доставка подтверждена!');
    }

    /**
     * @OA\Put (
     *     path="/courier-app/delivery/{invoiceId}/set-wait-list-status",
     *     summary="Поставить статус листа ожидания",
     *     operationId="setWaitListStatusForDelivery",
     *     tags={"Courier Deliveries"},
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
     * @param int $invoiceId
     * @param SetWaitListStatusRequest $request
     * @return MessagesResource
     * @throws AuthorizationException
     */
    public function setWaitListStatus(int $invoiceId, SetWaitListStatusRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::DELIVERY_SET_WAIT_LIST_STATUS);

        $this->dispatch(new SetDeliveryInfoWaitListStatusCommand($invoiceId, $request->getDTO()));

        return (new MessagesResource(null))
            ->setMessage('Статус листа ожидание успешно присвоен!');
    }
}
