# Laravel Simple Audit Log

Many systems need to log user actions for auditing purposes. This package creates a database table with sensible fields for logging actions.

## Features
* Table structure to keep audit logs
* Event driven
* Configurable connection if using a different database for recording logs
* Configurable model

## Table fields
This is only a dump to explain fields. Table will be created via Laravel migration file.

```php
Schema::connection(config('simple-audit-log.audit_log_db_connection'))->create('lsal_audit_logs', function (Blueprint $table) {
    $table->dateTime('al_date_time_local', $precision = 0)->index('al_date_time_local_index')->comment('Timestamp in local timezone.');
    $table->id();
    $table->dateTime('al_date_time_utc', $precision = 0)->nullable()->index('al_date_time_utc_index');
    $table->string('al_actor_id')->nullable()->index('al_actor_id_index')->comment('User id in application. Can be null in cases where an action is performed programmatically.');
    $table->string('al_actor_type', 255)->nullable()->index('al_actor_type_index')->comment('Actor type in application. Useful if you are logging multiple types of users. Example: admin, user, guest');
    $table->string('al_actor_global_uid')->nullable()->index('al_actor_global_uid_index')->comment('User id if using a single sign on facility.');
    $table->string('al_actor_username', 255)->nullable()->index('al_actor_username_index')->comment('Username in application.');
    $table->string('al_actor_group', 255)->nullable()->index('al_actor_group_index')->comment('User role/group in application.');
    $table->string('al_device_id', 255)->nullable()->index('al_device_id_index')->comment('Device identifier.');
    $table->string('al_target_name', 255)->nullable()->index('al_target_name_index')->comment('The object or underlying resource that is being accessed. Example: user.');
    $table->string('al_target_id')->nullable()->index('al_target_id_index')->comment('The ID of the resource that is being accessed.');
    $table->string('al_action_type', 255)->nullable()->index('al_action_type_index')->comment('CRUD: Read, write, update, delete');
    $table->string('al_event_name', 255)->index('al_event_name_index')->comment('Common name for the event that can be used to filter down to similar events. Example: user.login.success, user.login.failure, user.logout');
    $table->string('al_correlation_id', 255)->nullable()->index('al_correlation_id_index')->comment('Correlation id for easy traceability and joining with other tables.');
    $table->string('al_parent_correlation_id', 255)->nullable()->index('al_parent_correlation_id_index')->comment('Correlation id for easy traceability and joining with other tables.');
    $table->tinyInteger('al_is_success')->nullable()->default(0)->index('al_is_success_index');
    $table->text('al_meta')->nullable();
    $table->text('al_message')->nullable();
    $table->text('al_previous_value')->nullable();
    $table->text('al_new_value')->nullable();
    $table->text('al_request')->nullable()->comment('Request information.');
    $table->text('al_response')->nullable()->comment('Response information.');
    $table->ipAddress('al_ip_addr')->nullable()->index('al_ip_addr_index');
    $table->string('al_server', 255)->nullable()->index('al_server_index')->comment('Server ids or names, server location. Example: uat, production, testing, 192.168.2.10');
    $table->string('al_version', 255)->nullable()->index('al_version_index')->comment('Version of the code/release that is sending the events.');
    $table->timestamps();
});
```
## Events
You can dispatch these events to record logs. You can also listen to these events if you want additional processing.
* AuditLogged

## Requirements

* [Composer](https://getcomposer.org/)
* [Laravel](http://laravel.com/)
* [MySQL 4.x +](https://www.mysql.com/) VARBINARY data type is available as from 4.x

## Installation

You can install this package on an existing Laravel project with using composer:

```bash
 $ composer require aliirfaan/laravel-simple-audit-log
```

Register the ServiceProvider by editing **config/app.php** file and adding to providers array:

```php
  aliirfaan\LaravelSimpleAuditLog\SimpleAuditLogProvider::class,
```

Note: use the following for Laravel <5.1 versions:

```php
 'aliirfaan\LaravelSimpleAuditLog\SimpleAuditLogProvider',
```

Publish files with:

```bash
 $ php artisan vendor:publish --provider="aliirfaan\LaravelSimpleAuditLog\SimpleAuditLogProvider"
```

or by using only `php artisan vendor:publish` and select the `aliirfaan\LaravelSimpleAuditLog\SimpleAuditLogProvider` from the outputted list.

Apply the migrations:

```bash
 $ php artisan migrate
 ```

 ## Configuration

This package publishes an `simple-audit-log.php` file inside your applications's `config` folder which contains the settings for this package. Most of the variables are bound to environment variables, but you are free to directly edit this file, or add the configuration keys to the `.env` file.

audit_log_db_connection | String  
The database connection to use. Defaults to environment variable 'DB_CONNECTION'.

```php
'audit_log_db_connection' => env('AUDIT_LOG_DB_CONNECTION', env('DB_CONNECTION'))
```
audit_log_model | String
The model you want to use. The model must implement aliirfaan\LaravelSimpleAuditLog\Contracts\SimpleAuditLog

```php
'audit_log_model' => aliirfaan\LaravelSimpleAuditLog\Models\SimpleAuditLog::class,
```
## Usage

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use aliirfaan\LaravelSimpleAuditLog\Events\AuditLogged; // event you want to dispatch

class TestController extends Controller
{
    public function test(Request $request)
    {
        try {

            // log access after operation
            $eventData = [
                'al_date_time_local'=> date('Y-m-d H:i:s'),
                'al_date_time_utc'=> date('Y-m-d H:i:s'),
                'al_actor_id'=> 5,
                'al_event_name'=> 'user.login.success',
                'al_ip_addr'=> $request->ip()
            ];

            // dispatch event
            AuditLogged::dispatch($eventData);

        } catch (\Exception $e) {
            report($e);
        }
    }
}
```

### Custom model

If you have have additional requirements for our audit logs, you can add columns using migation and use a custom model to use your new columns.

Add your custom model to the configuration file.

```php
<?php

namespace App\Models\AuditLog;

use Illuminate\Database\Eloquent\Model;
use aliirfaan\LaravelSimpleAuditLog\Contracts\SimpleAuditLog as SimpleAuditLogContract;
use aliirfaan\LaravelSimpleAuditLog\Models\SimpleAuditLog;

// custom class that extends base model and implements contract
class AuditLog extends SimpleAuditLog implements SimpleAuditLogContract
{
    public function __construct(array $attributes = [])
    {
        // add your additional columns to the fillable property
        $this->mergeFillable(['al_custom_field_1']);
        
        parent::__construct($attributes);
    }
}
```

Specify custom model to configuration file
```php
'audit_log_model' => App\Models\AuditLog\AuditLog::class,
```

## License

The MIT License (MIT)

Copyright (c) 2020

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.