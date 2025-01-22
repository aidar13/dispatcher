<?php

namespace App\Providers;

use App\Models\User;
use App\Module\User\Events\UserCreatedEvent;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        Auth::viaRequest(
            'gateway-header',
            function (Request $request) {
                if ($userId = $request->header('X-User')) {
                    $user = User::firstOrCreate(['id' => $userId]);
                    event(new UserCreatedEvent((int)$userId));
                    return $user;
                }
                return null;
            }
        );
    }
}
