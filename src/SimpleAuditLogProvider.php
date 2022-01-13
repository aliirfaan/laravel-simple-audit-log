<?php

namespace aliirfaan\LaravelSimpleAuditLog;

use Illuminate\Support\ServiceProvider;
use aliirfaan\LaravelSimpleAuditLog\Providers\EventServiceProvider;

class SimpleAuditLogProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/simple-audit-log.php' => config_path('simple-audit-log.php'),
        ]);
    }
}
