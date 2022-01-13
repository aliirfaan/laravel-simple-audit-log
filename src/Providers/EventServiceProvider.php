<?php

namespace aliirfaan\LaravelSimpleAuditLog\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use aliirfaan\LaravelSimpleAuditLog\Listeners\AuditLogSubscriber;

class EventServiceProvider extends ServiceProvider
{
    protected $subscribe = [
        AuditLogSubscriber::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
