<?php

declare(strict_types=1);

namespace App\Module\Status\Queries;

use App\Module\Status\Contracts\Queries\CommentTemplateQuery as CommentTemplateQueryContract;
use App\Module\Status\DTO\CommentTemplateIndexDTO;
use App\Module\Status\Models\CommentTemplate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

final class CommentTemplateQuery implements CommentTemplateQueryContract
{
    public function getAllCommentTemplatesPaginated(CommentTemplateIndexDTO $DTO): LengthAwarePaginator
    {
        return CommentTemplate::query()
            ->when($DTO->typeId, fn(Builder $query) => $query->where('type_id', $DTO->typeId))
            ->paginate($DTO->limit, ['*'], 'page', $DTO->page);
    }
}
