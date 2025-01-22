<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * @psalm-suppress UndefinedInterfaceMethod
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->mapApiRoutes();

        if ($this->app->isLocal()) {
            //Роуты для Clockwork
            $this->registerFileRoute('clockwork');
        }

        // Диспетчерская
        $this->registerFileRoute('dispatcher');

        // Куреры
        $this->registerFileRoute('courier');

        // Файл
        $this->registerFileRoute('file');

        // Заборы
        $this->registerFileRoute('order-take');

        // Доставка
        $this->registerFileRoute('delivery');

        // Статусы
        $this->registerFileRoute('status');

        // Мониторниг
        $this->registerFileRoute('monitoring');

        // Заказ/Накладной
        $this->registerFileRoute('order');

        // Планирование
        $this->registerFileRoute('planning');

        // Заборы для курьеров
        $this->registerFileRoute('courier-app');

        // Заборы для маршрутизации
        $this->registerFileRoute('routing');
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(200)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    protected function mapApiRoutes(): void
    {
        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    public function registerFileRoute(string $fileName): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path("routes/${fileName}.php"));
    }
}
