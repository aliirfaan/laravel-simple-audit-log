<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Audit log configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings. You are free to adjust these settings as needed.
    |
    | audit_log_db_connection | String
    | The database connection to use. Defaults to environment variable 'DB_CONNECTION'.
    |
    | audit_log_model | String
    | The model you want to use. The model must implement aliirfaan\LaravelSimpleAuditLog\Contracts\SimpleAuditLog
    */

    'audit_log_db_connection' => env('AUDIT_LOG_DB_CONNECTION', env('DB_CONNECTION')),
    'audit_log_model' => aliirfaan\LaravelSimpleAuditLog\Models\SimpleAuditLog::class,
];
