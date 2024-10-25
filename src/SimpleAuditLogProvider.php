<?php

namespace aliirfaan\LaravelSimpleAuditLog;

use Illuminate\Support\ServiceProvider;
use aliirfaan\LaravelSimpleAuditLog\Providers\EventServiceProvider;
use aliirfaan\LaravelSimpleAuditLog\Contracts\SimpleAuditLog as SimpleAuditLogContract;
use aliirfaan\LaravelSimpleAuditLog\Services\AuditLogService;

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

        $this->app->bind('aliirfaan\LaravelSimpleAuditLog\Services\AuditLogService', function ($app) {
            return new AuditLogService();
        });
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

        $this->registerModelBindings();
    }

    protected function registerModelBindings()
    {
        $config = $this->app->config['simple-audit-log'];

        if (! $config) {
            return;
        }

        $this->app->bind(SimpleAuditLogContract::class,  $config['audit_log_model']);
    }
}
