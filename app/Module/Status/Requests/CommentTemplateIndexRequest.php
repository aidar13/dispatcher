<?php

declare(strict_types=1);

namespace App\Module\Status\Requests;

use App\Module\Status\DTO\CommentTemplateIndexDTO;
use Illuminate\Foundation\Http\FormRequest;

final class CommentTemplateIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'limit'  => ['nullable', 'integer'],
            'page'   => ['nullable', 'integer'],
            'typeId' => ['nullable', 'int'],
        ];
    }

    public function getDTO(): CommentTemplateIndexDTO
    {
        return CommentTemplateIndexDTO::fromRequest($this);
    }
}
