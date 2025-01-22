<?php

declare(strict_types=1);

namespace App\Module\Status\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Status\Models\CommentTemplate;

/**
 * @OA\Schema(
 *     @OA\Property(property="id",type="integer", description="CommentTemplate Id", example=1),
 *     @OA\Property(property="text",type="string", description="Текст шаблона для комментария", example="Не назначен на курьера"),
 *     @OA\Property(property="type_id",type="integer", description="Тип коммендарий(забор или доставка)", example=1),
 * )
 * @property CommentTemplate $resource
 */
final class CommentTemplateResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'      => $this->resource->id,
            'text'    => $this->resource->text,
            'type_id' => $this->resource->type_id
        ];
    }
}
