<?php

declare(strict_types=1);

namespace App\Module\Planning\Contracts\Repositories;

use App\Module\Planning\Models\Container;

interface UpdateContainerRepository
{
    public function update(Container $container): void;
}
