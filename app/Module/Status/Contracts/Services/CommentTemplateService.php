<?php

declare(strict_types=1);

namespace App\Module\Status\Contracts\Services;

use App\Module\Status\DTO\CommentTemplateIndexDTO;
use Illuminate\Pagination\LengthAwarePaginator;

interface CommentTemplateService
{
    public function getCommentTemplatesPaginated(CommentTemplateIndexDTO $DTO): LengthAwarePaginator;
}
