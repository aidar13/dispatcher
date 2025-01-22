<?php

declare(strict_types=1);

namespace App\Module\Planning\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\Planning\Commands\DeleteContainerInvoiceCommand;
use App\Module\Planning\Commands\DeleteContainerInvoicesCommand;
use App\Module\Planning\Permissions\PermissionList;
use App\Module\Planning\Requests\DeleteContainerInvoicesRequest;
use Illuminate\Auth\Access\AuthorizationException;

final class ContainerInvoiceController extends Controller
{
    /**
     * @OA\Post (
     *     path="/container-invoices/detach",
     *     operationId="detachContainerInvoices",
     *     tags={"ContainerInvoice"},
     *     summary="Удалить накладные из контейнера",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/DeleteContainerInvoicesRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Накладная из контейнера удалена!"),
     *             @OA\Property(property="data", type="object",example=null),
     *             @OA\Property(property="code", type="integer", example=201),
     *         ),
     *         description="",
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @throws AuthorizationException
     */
    public function detach(DeleteContainerInvoicesRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::CONTAINER_INVOICE_DESTROY);

        $this->dispatch(new DeleteContainerInvoicesCommand($request->getDTO()));

        return (new MessagesResource(null))
            ->setMessage('Накладные из контейнера удалены!');
    }
}
