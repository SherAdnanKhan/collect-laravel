<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPersonTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_persons', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('project_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::table('persons_to_sessions', function(Blueprint $table) {
            $table->dropForeign(['person_id']);
            $table->dropForeign(['person_role_id']);

            $table->renameColumn('person_id', 'project_person_id');
            $table->renameColumn('person_role_id', 'project_person_role_id');
        });

        Schema::rename('persons_to_sessions', 'project_persons_to_sessions');
        Schema::rename('person_roles', 'project_person_roles');

        Schema::table('project_persons_to_sessions', function(Blueprint $table) {
            $table->foreign('project_person_role_id')->references('id')
                ->on('project_person_roles')
                ->onDelete('cascade');

            $table->foreign('project_person_id')->references('id')
                ->on('project_persons')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_persons');

        Schema::table('project_persons_to_sessions', function(Blueprint $table) {
            $table->dropForeign(['project_person_id']);
            $table->dropForeign(['project_person_role_id']);

            $table->renameColumn('project_person_id', 'person_id');
            $table->renameColumn('project_person_role_id', 'person_role_id');
        });

        Schema::rename('project_person_roles', 'person_roles');
        Schema::rename('project_persons_to_sessions', 'persons_to_sessions');

        Schema::table('persons_to_sessions', function(Blueprint $table) {
            $table->foreign('person_id')->references('id')->on('persons')
                ->onDelete('cascade');
            $table->foreign('person_role_id')->references('id')->on('person_roles')
                ->onDelete('cascade');
        });
    }
}
