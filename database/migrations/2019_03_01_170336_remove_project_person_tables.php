<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveProjectPersonTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('project_persons_to_sessions');
        Schema::dropIfExists('project_person_roles');
        Schema::dropIfExists('project_persons');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('project_person_roles', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('project_persons', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('project_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::create('persons_to_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('session_id');
            $table->foreign('session_id')->references('id')->on('sessions')
                ->onDelete('cascade');
            $table->unsignedInteger('project_person_id');
            $table->foreign('project_person_id')->references('id')->on('project_persons')
                ->onDelete('cascade');
            $table->unsignedInteger('project_person_role_id');
            $table->foreign('project_person_role_id')->references('id')->on('project_person_roles')
                ->onDelete('cascade');
            $table->unsignedInteger('instrument_id')->nullable();
        });
    }
}
