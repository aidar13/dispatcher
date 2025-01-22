<?php

declare(strict_types=1);

namespace App\Module\Planning\Repositories\Eloquent;

use App\Module\Planning\Contracts\Repositories\CreateContainerRepository;
use App\Module\Planning\Contracts\Repositories\DeleteContainerRepository;
use App\Module\Planning\Contracts\Repositories\UpdateContainerRepository;
use App\Module\Planning\Models\Container;
use Throwable;

final class ContainerRepository implements CreateContainerRepository, UpdateContainerRepository, DeleteContainerRepository
{
    /**
     * @throws Throwable
     */
    public function create(Container $container): void
    {
        $container->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    public function delete(Container $container): void
    {
        $container->deleteOrFail();
    }

    /**
     * @throws Throwable
     */
    public function update(Container $container): void
    {
        $container->saveOrFail();
    }
}
