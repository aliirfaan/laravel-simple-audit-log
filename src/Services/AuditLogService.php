<?php

namespace aliirfaan\LaravelSimpleAuditLog\Services;

use Illuminate\Support\Facades\App;

class AuditLogService
{
    /**
     * Method generatePreliminaryEventData
     *
     * @param Object $request request date
     * @param string $correlationId
     * @param Object $actor model
     *
     * @return array
     */
    public function generatePreliminaryAuditData($request = null, $correlationId = null, $actor = null)
    {
        $actorId = ($actor !== null) ? $actor->getAuthIdentifier() : null;
        $ipAddr = ($request !== null) ? $request->ip() : null;
        $environment = App::environment();
        $dateNow = date('Y-m-d H:i:s');

        return [
            'al_date_time' => $dateNow,
            'al_actor_id' => $actorId,
            'al_ip_addr' => $ipAddr,
            'al_server' => $environment,
            'al_correlation_id' => $correlationId
        ];
    }
}
