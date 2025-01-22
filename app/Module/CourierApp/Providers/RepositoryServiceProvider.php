<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Providers;

use App\Module\CourierApp\Contracts\Repositories\CourierCall\CreateCourierCallRepository;
use App\Module\CourierApp\Contracts\Repositories\CourierLocation\CreateCourierLocationRepository;
use App\Module\CourierApp\Contracts\Repositories\CourierPayment\CreateCourierPaymentRepository;
use App\Module\CourierApp\Contracts\Repositories\CourierState\CreateCourierStateRepository;
use App\Module\CourierApp\Contracts\Repositories\OrderTake\SetInvoiceCargoPackCodeRepository as SetInvoiceCargoPackCodeRepositoryContract;
use App\Module\CourierApp\Repositories\CourierCall\CourierCallRepository;
use App\Module\CourierApp\Repositories\CourierLocation\CourierLocationRepository;
use App\Module\CourierApp\Repositories\CourierPayment\CourierPaymentRepository;
use App\Module\CourierApp\Repositories\CourierState\CourierStateRepository;
use App\Module\CourierApp\Repositories\IntegrationOneC\SetInvoiceCargoPackCodeRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CreateCourierCallRepository::class     => CourierCallRepository::class,
        CreateCourierStateRepository::class    => CourierStateRepository::class,
        CreateCourierPaymentRepository::class  => CourierPaymentRepository::class,
        CreateCourierLocationRepository::class => CourierLocationRepository::class,

        SetInvoiceCargoPackCodeRepositoryContract::class => SetInvoiceCargoPackCodeRepository::class,
    ];
}
