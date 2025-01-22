<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class BaseResourceCollection extends ResourceCollection
{
    public $with = [
        'code'    => 0,
        'success' => true,
        'message' => '',
        "errors"  => [],
    ];
}
