<?php

declare(strict_types=1);

namespace App\Module\File\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessagesResource;
use App\Module\File\Commands\DeleteFileWithoutAwsCommand;
use App\Module\File\Commands\UploadFileCommand;
use App\Module\File\Permissions\PermissionList;
use App\Module\File\Requests\UploadFileRequest;
use App\Module\File\Resources\FileResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Facades\Auth;

final class FileController extends Controller
{
    public function __construct(private readonly Dispatcher $commandDispatcher)
    {
    }

    /**
     * @OA\Post(
     *     path="/file/upload",
     *     tags={"File"},
     *     summary="Загрузить файл в S3",
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/UploadFileRequest")
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
     *                     ref="#/components/schemas/FileResource"
     *                 )
     *             )
     *         ),
     *         description=""
     *     ),
     *     security={{
     *         "apiKey":{}
     *     }}
     * )
     * @param UploadFileRequest $request
     * @return FileResource
     */
    public function upload(UploadFileRequest $request): FileResource
    {
        $command = new UploadFileCommand(
            (int)Auth::id(),
            $request->getDTO(),
        );

        $file = $this->commandDispatcher->dispatch($command);

        return new FileResource($file);
    }


    /**
     * @OA\Delete (
     *     path="/file/{uuidHash}",
     *     summary="Удаление файла",
     *     operationId="destroyFile",
     *     tags={"File"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter(
     *         name="uuidHash",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Файл успешно удален"),
     *             @OA\Property(property="data", type="object",example=null),
     *             @OA\Property(property="code", type="integer", example=200),
     *         ),
     *         description="",
     *    ),
     * )
     * @throws AuthorizationException
     */
    public function destroy(string $uuidHash): MessagesResource
    {
        $this->authorize(PermissionList::FILE_DELETE);

        $this->dispatch(new DeleteFileWithoutAwsCommand($uuidHash));

        return (new MessagesResource(null))
            ->setMessage('Файл успешно удален');
    }
}
