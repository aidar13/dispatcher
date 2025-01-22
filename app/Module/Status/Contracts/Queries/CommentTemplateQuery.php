<?php

declare(strict_types=1);

namespace App\Module\Status\Contracts\Queries;

use App\Module\Status\DTO\CommentTemplateIndexDTO;
use Illuminate\Pagination\LengthAwarePaginator;

interface CommentTemplateQuery
{
    public function getAllCommentTemplatesPaginated(CommentTemplateIndexDTO $DTO): LengthAwarePaginator;
}
