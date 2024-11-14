<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateLsalAuditLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('simple-audit-log.audit_log_db_connection'))->create('lsal_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->dateTime('al_date_time', $precision = 0)->index('al_date_time_index');
            $table->string('al_actor_id')->nullable()->index('al_actor_id_index')->comment('User id in application. Can be null in cases where an action is performed programmatically.');
            $table->string('al_actor_type', 255)->nullable()->index('al_actor_type_index')->comment('Actor type in application. Useful if you are logging multiple types of users. Example: admin, user, guest');
            $table->string('al_actor_global_uid')->nullable()->index('al_actor_global_uid_index')->comment('User id if using a single sign on facility.');
            $table->string('al_actor_username', 255)->nullable()->index('al_actor_username_index')->comment('Username in application.');
            $table->string('al_actor_group', 255)->nullable()->index('al_actor_group_index')->comment('User role/group in application.');
            $table->string('al_device_id', 255)->nullable()->index('al_device_id_index')->comment('Device identifier.');
            $table->string('al_target_name', 255)->nullable()->index('al_target_name_index')->comment('The object or underlying resource that is being accessed. Example: user.');
            $table->string('al_target_id')->nullable()->index('al_target_id_index')->comment('The ID of the resource that is being accessed.');
            $table->string('al_action_type', 255)->nullable()->index('al_action_type_index')->comment('CRUD: Read, write, update, delete');
            $table->string('al_event_name', 255)->nullable()->index('al_event_name_index')->comment('Common name for the event that can be used to filter down to similar events. Example: user.login.success, user.login.failure, user.logout');
            $table->string('al_correlation_id', 255)->nullable()->index('al_correlation_id_index')->comment('Correlation id for easy traceability and joining with other tables.');
            $table->string('al_parent_correlation_id', 255)->nullable()->index('al_parent_correlation_id_index')->comment('Correlation id for easy traceability and joining with other tables.');
            $table->tinyInteger('al_is_success')->nullable()->default(0)->index('al_is_success_index');
            $table->text('al_url')->nullable();
            $table->text('al_meta')->nullable();
            $table->text('al_message')->nullable();
            $table->text('al_previous_value')->nullable();
            $table->text('al_new_value')->nullable();
            $table->text('al_request')->nullable()->comment('Request information.');
            $table->text('al_response')->nullable()->comment('Response information.');
            $table->ipAddress('al_ip_addr')->nullable()->index('al_ip_addr_index');
            $table->string('al_server', 255)->nullable()->index('al_server_index')->comment('Server ids or names, server location. Example: uat, production, testing, 192.168.2.10');
            $table->string('al_version', 255)->nullable()->index('al_version_index')->comment('Version of the code/release that is sending the events.');
            $table->string('al_log_level', 10)->nullable()->index('al_log_level_index')->comment('Log level.');
            $table->string('al_code', 50)->nullable()->index('al_code_index')->comment('Error code.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('simple-audit-log.audit_log_db_connection'))->dropIfExists('lsal_audit_logs');
    }
}
