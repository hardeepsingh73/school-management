<?php

namespace App\Providers;

use App\Exceptions\Handler;
use App\Helpers\Settings;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * This method is used to bind services, interfaces,
     * or override Laravel's default implementations.
     *
     * @return void
     */
    public function register(): void
    {
        // Bind our custom exception handler in place of Laravel's default
        $this->app->singleton(ExceptionHandler::class, Handler::class);
    }

    /**
     * Bootstrap any application services.
     *
     * This method runs after all service providers have been registered.
     * Ideal for initializing global application settings, macros, or policies.
     *
     * @return void
     */
    public function boot(): void
    {
        /**
         * Ensure default string length is safe for older MySQL versions (< 5.7.7)
         * which have index length limitations with utf8mb4.
         */
        Schema::defaultStringLength(191);

        /**
         * Force HTTPS in production environment for all generated URLs.
         */
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
            DB::prohibitDestructiveCommands(true);
            Model::shouldBeStrict();
        }

        /**
         * Global authorization override:
         * Allow super admin to bypass all Gate checks.
         * Returns `true` to grant, `null` to defer check to the normal Gate logic.
         */
        Gate::before(function ($user, $ability) {
            return $user->hasRole(Settings::get('role_super_admin', 'superadmin')) ? true : null;
        });

        /**
         * Configure rate limiting rules for API routes.
         */
        $this->configureRateLimiting();
    }

    /**
     * Define the application's rate limiters.
     *
     * Here we define the "api" rate limiter to allow:
     *  - 60 requests per minute for each authenticated user ID
     *  - Or IP address for guests
     *
     * @return void
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(
                $request->user()?->id ?: $request->ip()
            );
        });
    }
}
