<?php

declare(strict_types=1);

namespace App\Module\Status\Controllers;

use App\Http\Controllers\Controller;
use App\Module\Status\Contracts\Services\CommentTemplateService;
use App\Module\Status\Requests\CommentTemplateIndexRequest;
use App\Module\Status\Resources\CommentTemplatesResource;

final class CommentTemplateController extends Controller
{
    public function __construct(
        private readonly CommentTemplateService $service
    ) {
    }

    /**
     * @OA\Get(
     *     path="/comment-template",
     *     summary="Список шаблонов для комментария ЛО",
     *     operationId="getAllCommentTemplatesPaginated",
     *     tags={"Status Type"},
     *
     *     @OA\Parameter (ref="#/components/parameters/__x_user"),
     *     @OA\Parameter (ref="#/components/parameters/__page"),
     *     @OA\Parameter (ref="#/components/parameters/__limit"),
     *     @OA\Parameter(
     *         name="typeId",
     *         in="query",
     *         required=false,
     *         description="Тип комментариев(1=Забор, 2=Доставка)",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example=null),
     *             @OA\Property(property="code", type="integer", example="200"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/CommentTemplateResource")
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     */
    public function index(CommentTemplateIndexRequest $request): CommentTemplatesResource
    {
        return new CommentTemplatesResource($this->service->getCommentTemplatesPaginated($request->getDTO()));
    }
}
