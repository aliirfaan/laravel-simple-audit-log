<?php

namespace aliirfaan\LaravelSimpleAuditLog\Models;

use Illuminate\Database\Eloquent\Model;
use aliirfaan\LaravelSimpleAuditLog\Contracts\SimpleAuditLog as SimpleAuditLogContract;
use Illuminate\Database\Eloquent\MassPrunable;

class SimpleAuditLog extends Model implements SimpleAuditLogContract
{
    use MassPrunable;

    protected $table = 'lsal_audit_logs';

    protected $fillable = [
        'al_date_time_local',
        'al_date_time_utc',
        'al_actor_id',
        'al_actor_type',
        'al_actor_global_uid',
        'al_actor_username',
        'al_actor_group',
        'al_device_id',
        'al_target_name',
        'al_target_id',
        'al_action_type',
        'al_event_name',
        'al_correlation_id',
        'al_parent_correlation_id',
        'al_is_success',
        'al_meta',
        'al_message',
        'al_previous_value',
        'al_new_value',
        'al_request',
        'al_response',
        'al_ip_addr',
        'al_server',
        'al_version',
        'al_log_level',
        'al_code'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = config('simple-audit-log.audit_log_db_connection');
    }

    public function prunable()
    {
        if (config('simple-audit-log.should_prune')) {
            return static::where('created_at', '<=', now()->subMonths(intval(config('simple-audit-log.prune_month'))));
        }
    }
}
