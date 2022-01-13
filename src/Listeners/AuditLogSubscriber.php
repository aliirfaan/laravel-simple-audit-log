<?php

namespace aliirfaan\LaravelSimpleAuditLog\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use aliirfaan\LaravelSimpleAuditLog\Events\AuditLogged;
use aliirfaan\LaravelSimpleAuditLog\Models\AuditLog;

class AuditLogSubscriber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
            $insertLog = AuditLog::create($eventData);
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
