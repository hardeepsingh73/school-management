<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Class AuthServiceProvider
 *
 * This service provider is responsible for registering:
 *  - Model-to-policy mappings for authorization
 *  - Any additional Gate definitions
 *
 * Once mappings are defined here, Laravel will automatically resolve
 * the appropriate policy when you call:
 *     $this->authorize('action', $modelInstance);
 * or use @can/@cannot Blade directives.
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Map the User model to its authorization policy
        User::class => UserPolicy::class,
    ];

    /**
     * Register any application services related to authorization.
     *
     * This is where you could bind additional service dependencies
     * if needed for the authorization layer.
     *
     * @return void
     */
    public function register(): void
    {
        // Currently nothing extra is required here for policies
    }

    /**
     * Bootstrap any authorization services.
     *
     * This method is automatically called by Laravel on boot.
     * Here we:
     *  1. Register our defined policies with the Gate system
     *  2. Optionally define inline Gates if needed
     *
     * @return void
     */
    public function boot(): void
    {
        // Ensure all policies defined in $policies array are registered
        $this->registerPolicies();

        // Example of defining additional Gates inline (optional):
        /*
        Gate::define('view-admin-dashboard', function (User $user) {
            return $user->hasRole('admin');
        });
        */
    }
}
