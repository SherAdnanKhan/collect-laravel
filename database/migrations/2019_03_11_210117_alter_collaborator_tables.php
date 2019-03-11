<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollaboratorTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborators', function(Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable()->change();
        });

        Schema::table('collaborator_invites', function(Blueprint $table) {
            $table->unsignedInteger('collaborator_id')->after('id');
            $table->foreign('collaborator_id')->references('id')->on('collaborators')
                ->onDelete('cascade');
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
            $table->dropForeign(['collaborator_id']);
            $table->dropColumn('collaborator_id');
            $table->dropColumn('name');
        });

        Schema::table('collaborators', function(Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable(false)->change();
        });
    }
}
