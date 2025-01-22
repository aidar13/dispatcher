<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\DispatcherSector\Commands\CreateWaveCommand;
use App\Module\DispatcherSector\Commands\DeleteWaveCommand;
use App\Module\DispatcherSector\Commands\UpdateWaveCommand;
use App\Module\DispatcherSector\Contracts\Services\WaveService;
use App\Module\DispatcherSector\Permissions\PermissionList;
use App\Module\DispatcherSector\Requests\WaveRequest;
use App\Module\DispatcherSector\Requests\WaveShowRequest;
use App\Module\DispatcherSector\Resources\WaveInvoicesResource;
use App\Module\DispatcherSector\Resources\WaveResource;
use App\Module\DispatcherSector\Resources\WavesResource;
use Illuminate\Auth\Access\AuthorizationException;

final class WaveController extends Controller
{
    public function __construct(
        private readonly WaveService $waveService
    ) {
    }

    /**
     * @OA\Get (
     *     path="/waves",
     *     operationId="getAllWaves",
     *     tags={"Waves"},
     *     summary="Список всех волн по сектора диспетчера",
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
     *
     *     @OA\Response(
     *         response=200,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/WaveResource",
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
    public function index(WaveShowRequest $request): WavesResource
    {
        $this->authorize(PermissionList::WAVE_INDEX);

        return new WavesResource(
            $this->waveService->getAll($request->getDTO())
        );
    }

    /**
     * @OA\Get(
     *     path="/waves/{id}/invoices",
     *     summary="Получение накладных по ID волны",
     *     operationId="getWaveInvoices",
     *     tags={"Waves"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__id"),
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
     *         name="sectorId",
     *         in="query",
     *         description="ID сектора",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter (
     *         name="additionalServices",
     *         in="query",
     *         description="доп услуги",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="integer", example=1))
     *     ),
     *     @OA\Parameter (
     *         name="statusId",
     *         in="query",
     *         description="статус груза",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example=null),
     *             @OA\Property(property="code", type="integer", example="200"),
     *             @OA\Property(property="data", ref="#/components/schemas/WaveInvoicesResource")
     *         )
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     * @throws AuthorizationException
     */
    public function invoices(int $id, WaveShowRequest $request): WaveInvoicesResource
    {
        $this->authorize(PermissionList::WAVE_INVOICES);

        return new WaveInvoicesResource(
            $this->waveService->getByIdWithFilter($id, $request->getDTO())
        );
    }

    /**
     * @OA\Get (
     *     path="/waves/{id}",
     *     operationId="getWaveById",
     *     tags={"Waves"},
     *     summary="Получение волны по ID",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *
     *     @OA\Response(
     *         response=200,
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Детальный просмотр волны"),
     *              @OA\Property(
     *                   property="data",
     *                   ref="#/components/schemas/WaveResource"
     *              ),
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
    public function show(int $id): WaveResource
    {
        $this->authorize(PermissionList::WAVE_INDEX);

        return new WaveResource($this->waveService->getById($id));
    }

    /**
     * @OA\Post (
     *     path="/waves",
     *     operationId="storeWave",
     *     tags={"Waves"},
     *     summary="Добавить волну",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/WaveRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Волна создан!"),
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
    public function store(WaveRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::WAVE_STORE);

        $command = new CreateWaveCommand($request->getDTO());

        $this->dispatch($command);

        return (new MessagesResource(null))
            ->setMessage('Волна создан!');
    }

    /**
     * @OA\Put (
     *     path="/waves/{id}",
     *     operationId="updateWave",
     *     tags={"Waves"},
     *     summary="Обновить волну",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/WaveRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Волна обновлен!"),
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
    public function update(int $id, WaveRequest $request): MessagesResource
    {
        $this->authorize(PermissionList::WAVE_UPDATE);

        $command = new UpdateWaveCommand(
            $id,
            $request->getDTO()
        );

        $this->dispatch($command);

        return (new MessagesResource(null))
            ->setMessage('Волна обновлен!');
    }

    /**
     * @OA\Delete (
     *     path="/waves/{id}",
     *     operationId="deleteWave",
     *     tags={"Waves"},
     *     summary="Удалить волну",
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__id"),
     *
     *     @OA\Response(
     *         response=200,
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Волна удален!"),
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
        $this->authorize(PermissionList::WAVE_DELETE);

        $command = new DeleteWaveCommand($id);

        $this->dispatch($command);

        return (new MessagesResource(null))
            ->setMessage('Волна удален!');
    }
}
