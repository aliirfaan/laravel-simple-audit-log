# Laravel Simple Audit Log

Many systems need to log user actions for auditing purposes. This package creates a database table with sensible fields for logging actions.

## Features
* Table structure to keep audit logs
* Event driven
* Configurable connection if using a different database for recording logs

## Table fields
This is only a dump to explain fields. Table will be created via Laravel migration file.

```sql
CREATE TABLE `lsal_audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `al_date_time_local` datetime NOT NULL COMMENT 'Timestamp in local timezone.',
  `al_date_time_utc` datetime DEFAULT NULL,
  `al_actor_id` bigint(20) unsigned NOT NULL COMMENT 'User id in application. Can be null in cases where an action is performed programmatically.',
  `al_actor_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Actor type in application. Useful if you are logging multiple types of users. Example: admin, user, guest',
  `al_actor_global_uid` bigint(20) unsigned DEFAULT NULL COMMENT 'User id if using a single sign on facility.',
  `al_actor_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Username in application.',
  `al_actor_group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'User role/group in application.',
  `al_device_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Device identifier.',
  `al_target_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'The object or underlying resource that is being accessed. Example: user.',
  `al_target_id` bigint(20) unsigned DEFAULT NULL COMMENT 'The ID of the resource that is being accessed.',
  `al_action_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'CRUD: Read, write, update, delete',
  `al_event_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Common name for the event that can be used to filter down to similar events. Example: user.login.success, user.login.failure, user.logout',
  `al_previous_value` text COLLATE utf8mb4_unicode_ci,
  `al_new_value` text COLLATE utf8mb4_unicode_ci,
  `al_request` text COLLATE utf8mb4_unicode_ci COMMENT 'Request information.',
  `al_response` text COLLATE utf8mb4_unicode_ci COMMENT 'Response information.',
  `al_custom_field_1` text COLLATE utf8mb4_unicode_ci,
  `al_custom_field_2` text COLLATE utf8mb4_unicode_ci,
  `al_custom_field_3` text COLLATE utf8mb4_unicode_ci,
  `al_ip_addr` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `al_server` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Server ids or names, server location. Example: uat, production, testing, 192.168.2.10',
  `al_version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Version of the code/release that is sending the events.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `al_date_time_local_index` (`al_date_time_local`),
  KEY `al_date_time_utc_index` (`al_date_time_utc`),
  KEY `al_actor_id_index` (`al_actor_id`),
  KEY `al_actor_type_index` (`al_actor_type`),
  KEY `al_actor_global_uid_index` (`al_actor_global_uid`),
  KEY `al_actor_username_index` (`al_actor_username`),
  KEY `al_actor_group_index` (`al_actor_group`),
  KEY `al_device_id_index` (`al_device_id`),
  KEY `al_target_name_index` (`al_target_name`),
  KEY `al_target_id_index` (`al_target_id`),
  KEY `al_action_type_index` (`al_action_type`),
  KEY `al_event_name_index` (`al_event_name`),
  KEY `al_ip_addr_index` (`al_ip_addr`),
  KEY `al_server_index` (`al_server`),
  KEY `al_version_index` (`al_version`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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