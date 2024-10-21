# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com) and this project adheres to [Semantic Versioning](https://semver.org).

## 9.0.0 - 2024-10-21

### Added

- Nothing

### Changed

- renamed column al_date_time_local to al_date_time
- make column al_event_name nullable
- change pruning configuration from month to days
- migration file: 2022_01_11_133615_create_lsal_audit_logs_table.php
- model
- configuration

### Deprecated

- Nothing

### Removed

- removed column al_date_time_utc

### Fixed

- Nothing

## 8.0.0 - 2024-05-13

### Added

- Nothing

### Changed

- al_code column to migration
- src\Models\SimpleAuditLog.php - added column to fillable
- README.md

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 7.0.0 - 2024-05-07

### Added

- Nothing

### Changed

- al_log_level column to migration
- src\Models\SimpleAuditLog.php - added column to fillable
- README.md

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 6.0.0 - 2023-06-20

### Added

- config should_prune - whether to enable pruning
- config prune_month - to delete records older than prune_months

### Changed

- class SimpleAuditLog
- config file simple-audit-log.php

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 5.0.1 - 2023-01-18

### Added

- Nothing

### Changed

- Path for config and database

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- AuditLogSubscriber handleAuditLogEvent($event) set data['success'] to true on model create success

## 5.0.0 - 2022-09-13

### Added

- Nothing

### Changed

- Path for config and database

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 4.0.0 - 2022-07-27

### Added

- al_parent_correlation_id, al_is_success, al_meta, al_message columns
- audit_log_model config to use custom model
- Contracts\SimpleAuditLog

### Changed

- migration file
- al_parent_correlation_id for easy joining with other tables/microservices
- al_is_success
- al_meta
- al_message
- AuditLog Model
- config file
- AuditLogSubscriber - use model set in config
- renamed model to SimpleAuditLog

### Deprecated

- Nothing

### Removed

- all custom fields as we can extend the model and add column using custom migration

### Fixed

- Nothing

## 3.0.0 - 2022-07-26

### Added

- al_correlation_id column

### Changed

- migration file
- al_correlation_id for easy joining with other tables/microservices
- AuditLog Model

### Deprecated

- Nothing

### Removed

- nothing

### Fixed

- Nothing

## 2.0.0 - 2022-07-26

### Added

- Nothing

### Changed

- migration file
- al_actor_id column nullable, as string to support UUID
- al_target_id column as string

### Deprecated

- Nothing

### Removed

- nothing

### Fixed

- Nothing