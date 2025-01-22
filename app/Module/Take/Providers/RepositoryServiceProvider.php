<?php

declare(strict_types=1);

namespace App\Module\Take\Providers;

use App\Module\CourierApp\Handlers\OrderTake\IntegrationOneC\ChangeOrderTakeStatusInOneCHandler;
use App\Module\Take\Contracts\Repositories\CreateCustomerRepository;
use App\Module\Take\Contracts\Repositories\CreateOrderTakeRepository;
use App\Module\Take\Contracts\Repositories\Integration\AssignCourierToOrderIn1CRepository as AssignCourierToOrderIn1CRepositoryContract;
use App\Module\Take\Contracts\Repositories\Integration\IntegrationOrderStatusRepository;
use App\Module\Take\Contracts\Repositories\Integration\SetWaitListStatusRepositoryIntegration as SetTakeWaitListStatusRepositoryIntegrationContract;
use App\Module\Take\Contracts\Repositories\UpdateCustomerRepository;
use App\Module\Take\Contracts\Repositories\UpdateOrderTakeRepository;
use App\Module\Take\Repositories\Eloquent\CustomerRepository;
use App\Module\Take\Repositories\Eloquent\Integration\AssignCourierToOrderIn1CRepository;
use App\Module\Take\Repositories\Eloquent\Integration\IntegrationOrderStatusRepository as IntegrationOrderStatusRepositoryContract;
use App\Module\Take\Repositories\Eloquent\Integration\SetTakeWaitListStatusRepositoryIntegration;
use App\Module\Take\Repositories\Eloquent\OrderTakeRepository;
use App\Module\Take\Repositories\OneC\OrderTakeOneCRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CreateOrderTakeRepository::class                          => OrderTakeRepository::class,
        UpdateOrderTakeRepository::class                          => OrderTakeRepository::class,
        CreateCustomerRepository::class                           => CustomerRepository::class,
        UpdateCustomerRepository::class                           => CustomerRepository::class,
        IntegrationOrderStatusRepository::class                   => IntegrationOrderStatusRepositoryContract::class,
        AssignCourierToOrderIn1CRepositoryContract::class         => AssignCourierToOrderIn1CRepository::class,
        SetTakeWaitListStatusRepositoryIntegrationContract::class => SetTakeWaitListStatusRepositoryIntegration::class,
    ];

    public function register(): void
    {
        $this->app->when(ChangeOrderTakeStatusInOneCHandler::class)
            ->needs(UpdateOrderTakeRepository::class)
            ->give(OrderTakeOneCRepository::class);
    }
}
