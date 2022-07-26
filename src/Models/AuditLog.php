<?php

namespace aliirfaan\LaravelSimpleAuditLog\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
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
        'al_previous_value', 
        'al_new_value', 
        'al_request', 
        'al_response', 
        'al_custom_field_1', 
        'al_custom_field_2', 
        'al_custom_field_3', 
        'al_ip_addr', 
        'al_server', 
        'al_version'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = config('simple-audit-log.audit_log_db_connection');
    }
}