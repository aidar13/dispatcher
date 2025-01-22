<?php

declare(strict_types=1);

namespace App\Module\Status\Services;

use App\Module\Status\Contracts\Queries\CommentTemplateQuery;
use App\Module\Status\Contracts\Services\CommentTemplateService as CommentTemplateServiceContract;
use App\Module\Status\DTO\CommentTemplateIndexDTO;
use Illuminate\Pagination\LengthAwarePaginator;

final class CommentTemplateService implements CommentTemplateServiceContract
{
    public function __construct(
        public CommentTemplateQuery $query
    ) {
    }
    public function getCommentTemplatesPaginated(CommentTemplateIndexDTO $DTO): LengthAwarePaginator
    {
        return $this->query->getAllCommentTemplatesPaginated($DTO);
    }
}
