<?php

declare(strict_types=1);

namespace App\Module\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\Order\Commands\ResendDeliveryStatusToOneCByInvoiceIdCommand;
use App\Module\Order\Commands\SetInvoicesWaveCommand;
use App\Module\Order\Commands\SetInvoiceWaveCommand;
use App\Module\Order\Commands\UpdateInvoiceDeliveryDateCommand;
use App\Module\Order\Contracts\Services\InvoiceService;
use App\Module\Order\Permissions\PermissionList;
use App\Module\Order\Requests\InvoiceShowRequest;
use App\Module\Order\Requests\UpdateInvoiceDeliveryDateRequest;
use App\Module\Order\Requests\UpdateInvoicesWaveRequest;
use App\Module\Order\Requests\UpdateInvoiceWaveRequest;
use App\Module\Order\Resources\InvoiceProblemsResource;
use App\Module\Order\Resources\InvoicesResource;
use App\Module\Status\Models\RefStatus;
use Illuminate\Auth\Access\AuthorizationException;

final class InvoiceController extends Controller
{
    public function __construct(private readonly InvoiceService $service)
    {
    }

    /**
     * @OA\Put (
     *     path="/invoices/{id}/delivery-date",
     *     tags={"Invoice"},
     *     summary="Перенести дату доставки",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/UpdateInvoiceDeliveryDateRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *        response=200,
     *        description="",
     *        @OA\JsonContent(
     *            @OA\Property(property="success", type="boolean", example=true),
     *            @OA\Property(property="message", type="string", example="Дата доставки изменен!"),
     *            @OA\Property(property="data", type="object",example=null),
     *            @OA\Property(property="code", type="integer", example=200),
     *        ),
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @throws AuthorizationException
     */
    public function updateDeliveryDate(int $id, UpdateInvoiceDeliveryDateRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::INVOICE_CHANGE_DELIVERY_DATE);

        $this->dispatch(new UpdateInvoiceDeliveryDateCommand(
            $id,
            $request->get('date')
        ));

        return (new MessagesResource(null))
            ->setMessage('Дата доставки изменен!');
    }

    /**
     * @OA\Put (
     *     path="/invoices/{id}/wave",
     *     tags={"Invoice"},
     *     summary="Изменить, переотределить волну накладной",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/UpdateInvoiceWaveRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *        response=200,
     *        description="",
     *        @OA\JsonContent(
     *            @OA\Property(property="success", type="boolean", example=true),
     *            @OA\Property(property="message", type="string", example="Волна изменена!"),
     *            @OA\Property(property="data", type="object",example=null),
     *            @OA\Property(property="code", type="integer", example=200),
     *        ),
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @throws AuthorizationException
     */
    public function updateWave(int $invoiceId, UpdateInvoiceWaveRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::INVOICE_UPDATE_WAVE);

        $this->dispatchSync(new SetInvoiceWaveCommand(
            $invoiceId,
            (int)$request->get('waveId') ?: null
        ));

        return (new MessagesResource(null))
            ->setMessage('Волна изменена!');
    }

    /**
     * @OA\Put (
     *     path="/invoices/set-wave",
     *     tags={"Invoice"},
     *     summary="Изменить волну накладной",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/UpdateInvoicesWaveRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *        response=200,
     *        description="",
     *        @OA\JsonContent(
     *            @OA\Property(property="success", type="boolean", example=true),
     *            @OA\Property(property="message", type="string", example="Волна изменена!"),
     *            @OA\Property(property="data", type="object",example=null),
     *            @OA\Property(property="code", type="integer", example=200),
     *        ),
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @throws AuthorizationException
     */
    public function updateWaves(UpdateInvoicesWaveRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::INVOICE_UPDATE_WAVE);

        $this->dispatchSync(new SetInvoicesWaveCommand(
            (int)$request->get('waveId'),
            $request->get('invoiceIds')
        ));

        return (new MessagesResource(null))
            ->setMessage('Волна изменена!');
    }

    /**
     * @OA\Get (
     *     path="/invoices/on-hold",
     *     operationId="getInvoicesOnHold",
     *     tags={"Invoice"},
     *     summary="Получение накладных которые на хранении",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\Parameter (
     *         name="dispatcherSectorId",
     *         in="query",
     *         description="ID диспетчер сектора",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example="1",
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="date",
     *         in="query",
     *         description="Дата планирование",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="2023-08-18",
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="invoiceNumber",
     *         in="query",
     *         description="Номер накладной",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="SP0000001",
     *         )
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
     *                     ref="#/components/schemas/InvoicesResource",
     *                 )
     *             )
     *         ),
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @param InvoiceShowRequest $request
     * @return InvoicesResource
     */
    public function invoicesOnHold(InvoiceShowRequest $request): InvoicesResource
    {
        $DTO = $request->getDTO();
        $DTO->setWaitListStatus(RefStatus::ID_ON_HOLD);

        return new InvoicesResource($this->service->getInvoices($DTO));
    }

    /**
     * @OA\Get (
     *     path="/invoices/{invoiceId}/problems",
     *     operationId="getInvoicePromlems",
     *     tags={"Invoice"},
     *     summary="Получение проблемностей накладной",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="data",
     *                     ref="#/components/schemas/InvoiceProblemsResource",
     *                 )
     *             )
     *         ),
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @param int $invoiceId
     * @return InvoiceProblemsResource
     */
    public function getProblems(int $invoiceId): InvoiceProblemsResource
    {
        return new InvoiceProblemsResource($this->service->getInvoiceProblemsById($invoiceId));
    }

    /**
     * @OA\Post (
     *     path="/invoices/{invoiceId}/resend-status-onec",
     *     operationId="resendInvoiceStatus",
     *     tags={"Invoice"},
     *     summary="Переотправить статус доставки в 1С",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *
     *     @OA\Response(
     *        response=200,
     *        description="Статус успешно переотправлен!",
     *        @OA\JsonContent(
     *            @OA\Property(property="success", type="boolean", example=true),
     *            @OA\Property(property="message", type="string", example="Статус успешно переотправлен!"),
     *            @OA\Property(property="data", type="object",example=null),
     *            @OA\Property(property="code", type="integer", example=200),
     *        ),
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @param int $invoiceId
     * @return MessagesResource
     * @throws AuthorizationException
     */
    public function resendStatusToOneC(int $invoiceId): MessagesResource
    {
        $this->authorize(PermissionList::RESEND_STATUS_TO_ONE_C);

        $this->dispatchSync(new ResendDeliveryStatusToOneCByInvoiceIdCommand($invoiceId));

        return (new MessagesResource(null))
            ->setMessage('Статус успешно переотправлен!');
    }
}
