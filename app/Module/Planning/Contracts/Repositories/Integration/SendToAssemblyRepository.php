<?php

declare(strict_types=1);

namespace App\Module\Planning\Contracts\Repositories\Integration;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

interface SendToAssemblyRepository
{
    public function send(EloquentCollection $containers): Collection;
}
