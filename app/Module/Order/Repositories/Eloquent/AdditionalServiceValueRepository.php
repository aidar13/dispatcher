<?php

declare(strict_types=1);

namespace App\Module\Order\Repositories\Eloquent;

use App\Module\Order\Contracts\Repositories\CreateAdditionalServiceValueRepository;
use App\Module\Order\Contracts\Repositories\DeleteAdditionalServiceValueRepository;
use App\Module\Order\Contracts\Repositories\UpdateAdditionalServiceValueRepository;
use App\Module\Order\Models\AdditionalServiceValue;
use Throwable;

final class AdditionalServiceValueRepository implements CreateAdditionalServiceValueRepository, UpdateAdditionalServiceValueRepository, DeleteAdditionalServiceValueRepository
{
    /**
     * @throws Throwable
     */
    public function create(AdditionalServiceValue $service): void
    {
        $service->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    public function update(AdditionalServiceValue $service): void
    {
        $service->saveOrFail();
    }

    public function remove(AdditionalServiceValue $service): void
    {
        $service->delete();
    }
}
