<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaboratorInvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborator_invites', function(Blueprint $table) {
            $table->unsignedInteger('collaborator_id')->nullable(true)->change();
            $table->dropColumn('email');
            $table->dropColumn('name');
        });

        Schema::table('collaborators', function(Blueprint $table) {
            $table->string('email')->after('project_id');
            $table->string('name')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborator_invites', function(Blueprint $table) {
            $table->unsignedInteger('collaborator_id')->nullable(false)->change();
            $table->string('email');
            $table->string('name');
        });

        Schema::table('collaborators', function(Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('name');
        });
    }
}
