<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\CourierApp\Commands\CourierPayment\SaveCourierPaymentFilesCommand;
use App\Module\CourierApp\Permissions\PermissionList;
use App\Module\CourierApp\Requests\CourierPayment\SaveCourierPaymentFilesRequest;
use App\Module\CourierApp\Resources\CourierPayment\CourierPaymentsResource;
use App\Module\Order\Contracts\Services\InvoiceService;
use App\Module\Order\Contracts\Services\OrderService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

final class CourierPaymentController extends Controller
{
    public function __construct(
        private readonly InvoiceService $invoiceService,
        private readonly OrderService $orderService,
    ) {
    }

    /**
     * @OA\Post(
     *     path="/courier-app/order-take/courier-payment",
     *     summary="Загрузка файлов чеков курьера",
     *     description="Загрузка файлов чеков курьера",
     *     operationId="saveOrderTakeCourierPaymentFiles",
     *     tags={"Courier Payments"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/SaveCourierPaymentFilesRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Файлы успешно сохранены!"),
     *             @OA\Property(property="data", type="object", example=null),
     *             @OA\Property(property="code", type="integer", example=201),
     *         )
     *     ),
     *     security={{
     *         "bearer": {}
     *     }}
     * )
     * @param SaveCourierPaymentFilesRequest $request
     * @return MessagesResource
     * @throws AuthorizationException
     */
    public function saveOrderTakeCourierPaymentFiles(SaveCourierPaymentFilesRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::COURIER_PAYMENT_STORE);

        $this->dispatch(new SaveCourierPaymentFilesCommand((int)Auth::id(), $request->getOrderTakeDTO()));

        return (new MessagesResource(null))->setMessage('Файлы успешно сохранены!');
    }

    /**
     * @OA\Post(
     *     path="/courier-app/delivery/courier-payment",
     *     summary="Загрузка файлов чеков курьера",
     *     description="Загрузка файлов чеков курьера",
     *     operationId="saveDeliveryCourierPaymentFiles",
     *     tags={"Courier Payments"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/SaveCourierPaymentFilesRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Файлы успешно сохранены!"),
     *             @OA\Property(property="data", type="object", example=null),
     *             @OA\Property(property="code", type="integer", example=201),
     *         )
     *     ),
     *     security={{
     *         "bearer": {}
     *     }}
     * )
     * @param SaveCourierPaymentFilesRequest $request
     * @return MessagesResource
     * @throws AuthorizationException
     */
    public function saveDeliveryCourierPaymentFiles(SaveCourierPaymentFilesRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::COURIER_PAYMENT_STORE);

        $this->dispatch(new SaveCourierPaymentFilesCommand((int)Auth::id(), $request->getDeliveryDTO()));

        return (new MessagesResource(null))->setMessage('Файлы успешно сохранены!');
    }

    /**
     * @OA\Get (
     *     path="/courier-app/delivery/courier-payment/{invoiceId}",
     *     tags={"Courier Payments"},
     *     operationId="showByInvoiceId",
     *     summary="Просмотр платного проезда/парковки по накладной",
     *
     *     @OA\Parameter(
     *         name="invoiceId",
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
     *                     ref="#/components/schemas/CourierPaymentResource"
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
     * @param int $invoiceId
     * @return CourierPaymentsResource
     * @throws AuthorizationException
     */
    public function showByInvoiceId(int $invoiceId): CourierPaymentsResource
    {
        $this->authorize(PermissionList::COURIER_PAYMENT_SHOW);

        $invoice = $this->invoiceService->getById($invoiceId, ['id'], ['courierPayments']);

        return new CourierPaymentsResource($invoice->courierPayments ?? collect());
    }

    /**
     * @OA\Get (
     *     path="/courier-app/order-take/courier-payment/{orderId}",
     *     tags={"Courier Payments"},
     *     operationId="showByInvoiceId",
     *     summary="Просмотр платного проезда/парковки по заказу",
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
     *                     ref="#/components/schemas/CourierPaymentResource"
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
     * @return CourierPaymentsResource
     * @throws AuthorizationException
     */
    public function showByOrderId(int $orderId): CourierPaymentsResource
    {
        $this->authorize(PermissionList::COURIER_PAYMENT_SHOW);

        $order = $this->orderService->getById($orderId, ['id'], ['courierPayments']);

        return new CourierPaymentsResource($order->courierPayments ?? collect());
    }
}
