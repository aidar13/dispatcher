<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\DispatcherSector\Commands\CreateSectorCommand;
use App\Module\DispatcherSector\Commands\DeleteSectorCommand;
use App\Module\DispatcherSector\Commands\UpdateSectorCommand;
use App\Module\DispatcherSector\Contracts\Services\SectorService;
use App\Module\DispatcherSector\Permissions\PermissionList;
use App\Module\DispatcherSector\Requests\CreateSectorRequest;
use App\Module\DispatcherSector\Requests\SectorShowRequest;
use App\Module\DispatcherSector\Requests\UpdateSectorRequest;
use App\Module\DispatcherSector\Resources\SectorsResource;
use Illuminate\Auth\Access\AuthorizationException;

final class SectorController extends Controller
{
    public function __construct(
        private readonly SectorService $service
    ) {
    }

    /**
     * @OA\Get (
     *     path="/sectors",
     *     tags={"Sector"},
     *     summary="Список всех секторов",
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
     *             example="Сектор 1",
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="cityId",
     *         in="query",
     *         description="Айди города",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=1,
     *         )
     *     ),
     *     @OA\Parameter (
     *         name="dispatcherSectorIds",
     *         in="query",
     *         description="IDs диспетчер сектора",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="integer", example=1))
     *     ),
     *
     *     @OA\Response(
     *        response=200,
     *        @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                @OA\Property(
     *                    property="data",
     *                    ref="#/components/schemas/SectorShowResource",
     *                )
     *            )
     *        ),
     *        description=""
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @throws AuthorizationException
     */
    public function index(SectorShowRequest $request): SectorsResource
    {
        $this->authorize(PermissionList::SECTOR_INDEX);

        return new SectorsResource(
            $this->service->getAllSectorsPaginated($request->getDTO())
        );
    }

    /**
     * @OA\Post (
     *     path="/sectors",
     *     tags={"Sector"},
     *     summary="Добавить Сектор",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CreateSectorRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *        response=200,
     *        description="",
     *        @OA\JsonContent(
     *            @OA\Property(property="success", type="boolean", example=true),
     *            @OA\Property(property="message", type="string", example="Сектор создан!"),
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
    public function store(CreateSectorRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::SECTOR_STORE);

        $command = new CreateSectorCommand($request->getDTO());

        $this->dispatch($command);

        return (new MessagesResource(null))
            ->setMessage('Сектор создан!');
    }

    /**
     * @OA\Put (
     *     path="/sectors/{id}",
     *     tags={"Sector"},
     *     summary="Обновить Сектор",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/UpdateSectorRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *        response=200,
     *        description="",
     *        @OA\JsonContent(
     *            @OA\Property(property="success", type="boolean", example=true),
     *            @OA\Property(property="message", type="string", example="Сектор обновлен!"),
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
    public function update(UpdateSectorRequest $request, int $id): MessagesResource
    {
        $this->authorize(PermissionList::SECTOR_UPDATE);

        $command = new UpdateSectorCommand(
            $id,
            $request->getDTO()
        );

        $this->dispatch($command);

        return (new MessagesResource(null))
            ->setMessage('Сектор обновлен!');
    }

    /**
     * @OA\Delete (
     *     path="/sectors/{id}",
     *     tags={"Sector"},
     *     summary="Удалить Сектор",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *
     *     @OA\Response(
     *        response=200,
     *        description="",
     *        @OA\JsonContent(
     *            @OA\Property(property="success", type="boolean", example=true),
     *            @OA\Property(property="message", type="string", example="Сектор удален!"),
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
    public function destroy(int $id): MessagesResource
    {
        $this->authorize(PermissionList::SECTOR_DELETE);

        $command = new DeleteSectorCommand($id);

        $this->dispatch($command);

        return (new MessagesResource(null))
            ->setMessage('Сектор удален!');
    }
}
