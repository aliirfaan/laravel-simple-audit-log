<?php

namespace aliirfaan\LaravelSimpleAuditLog\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

use aliirfaan\LaravelSimpleAuditLog\Events\AuditLogged;

class AuditLogSubscriber
{
    private $model;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = app(config('simple-audit-log.audit_log_model'));
    }

    public function handleAuditLogEvent($event)
    {
        $data = [
            'success' => false,
            'result' => null,
            'message' => null
        ];

        try {
            $eventData = $event->eventData;
            $insertLog = $this->model::create($eventData);
        } catch (\Exception $e) {
            report($e);
    
            $data['message'] = 'Audit log could not be saved.';
        }
    
        return $data;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {

        $events->listen(
            AuditLogged::class,
            [AuditLogSubscriber::class, 'handleAuditLogEvent']
        );
    }
}
