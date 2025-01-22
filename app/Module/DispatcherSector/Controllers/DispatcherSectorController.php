<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\DispatcherSector\Commands\CreateDispatcherSectorCommand;
use App\Module\DispatcherSector\Commands\DeleteDispatcherSectorCommand;
use App\Module\DispatcherSector\Commands\UpdateDispatcherSectorCommand;
use App\Module\DispatcherSector\Contracts\Services\DispatcherSectorService;
use App\Module\DispatcherSector\Permissions\PermissionList;
use App\Module\DispatcherSector\Requests\CreateDispatcherSectorRequest;
use App\Module\DispatcherSector\Requests\DispatcherSectorShowRequest;
use App\Module\DispatcherSector\Requests\UpdateDispatcherSectorRequest;
use App\Module\DispatcherSector\Resources\DispatcherSectorsResource;
use Illuminate\Auth\Access\AuthorizationException;

final class DispatcherSectorController extends Controller
{
    public function __construct(
        private readonly DispatcherSectorService $service
    ) {
    }

    /**
     * @OA\Get (
     *     path="/dispatcher-sectors",
     *     tags={"Dispatcher Sector"},
     *     summary="Список всех диспетчер секторов с пагинацией",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__limit"),
     *     @OA\Parameter (ref="#/components/parameters/__page"),
     *     @OA\Parameter (
     *         name="name",
     *         in="query",
     *         description="Название сектора",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="Диспетчер Сектор 1",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/DispatcherSectorResource",
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
    public function index(DispatcherSectorShowRequest $request): DispatcherSectorsResource
    {
        $this->authorize(PermissionList::DISPATCHER_SECTOR_INDEX);

        return new DispatcherSectorsResource(
            $this->service->getAllDispatcherSectorsPaginated($request->getDTO())
        );
    }

    /**
     * @OA\Get (
     *     path="/dispatcher-sectors/all",
     *     tags={"Dispatcher Sector"},
     *     summary="Список секторов диспетчера без пагинации",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Response(
     *         response=200,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/DispatcherSectorResource",
     *                 )
     *             )
     *        ),
     *        description=""
     *    ),
     *    security={{
     *        "apiKey":{}
     *    }}
     * )
     * @throws AuthorizationException
     */
    public function getAll(): DispatcherSectorsResource
    {
        $this->authorize(PermissionList::DISPATCHER_SECTOR_INDEX);

        return new DispatcherSectorsResource(
            $this->service->getAllDispatcherSectorsActiveUsers()
        );
    }

    /**
     * @OA\Post (
     *     path="/dispatcher-sectors",
     *     tags={"Dispatcher Sector"},
     *     summary="Добавить Диспетчер Сектор",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CreateDispatcherSectorRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Сектор диспетчера создан!"),
     *             @OA\Property(property="data", type="object",example=null),
     *             @OA\Property(property="code", type="integer", example=200),
     *         ),
     *     description="",
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @throws AuthorizationException
     */
    public function store(CreateDispatcherSectorRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::DISPATCHER_SECTOR_STORE);

        $command = new CreateDispatcherSectorCommand($request->getDTO());

        $this->dispatch($command);

        return (new MessagesResource(null))
            ->setMessage('Сектор диспетчера создан!');
    }

    /**
     * @OA\Put (
     *     path="/dispatcher-sectors/{id}",
     *     tags={"Dispatcher Sector"},
     *     summary="Обновить Диспетчер Сектор",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/UpdateDispatcherSectorRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Сектор диспетчера обновлен!"),
     *             @OA\Property(property="data", type="object",example=null),
     *             @OA\Property(property="code", type="integer", example=200),
     *         ),
     *     description="",
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @throws AuthorizationException
     */
    public function update(int $id, UpdateDispatcherSectorRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::DISPATCHER_SECTOR_UPDATE);

        $command = new UpdateDispatcherSectorCommand(
            $id,
            $request->getDTO()
        );

        $this->dispatch($command);

        return (new MessagesResource(null))
            ->setMessage('Сектор диспетчера обновлен!');
    }

    /**
     * @OA\Delete (
     *     path="/dispatcher-sectors/{id}",
     *     tags={"Dispatcher Sector"},
     *     summary="Удалить Диспетчер Сектор",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *
     *     @OA\Response(
     *         response=200,
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Сектор диспетчера удален!"),
     *             @OA\Property(property="data", type="object",example=null),
     *             @OA\Property(property="code", type="integer", example=200),
     *         ),
     *     description="",
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @throws AuthorizationException
     */
    public function destroy(int $id): MessagesResource
    {
        $this->authorize(PermissionList::DISPATCHER_SECTOR_DELETE);

        $command = new DeleteDispatcherSectorCommand($id);

        $this->dispatch($command);

        return (new MessagesResource(null))
            ->setMessage('Сектор диспетчера удален!');
    }
}
