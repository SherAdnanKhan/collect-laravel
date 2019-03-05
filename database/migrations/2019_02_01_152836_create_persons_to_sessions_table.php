<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonsToSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons_to_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('session_id');
            $table->foreign('session_id')->references('id')->on('sessions')
                ->onDelete('cascade');
            $table->unsignedInteger('person_id');
            $table->foreign('person_id')->references('id')->on('persons')
                ->onDelete('cascade');
            $table->unsignedInteger('person_role_id');
            $table->foreign('person_role_id')->references('id')->on('person_roles')
                ->onDelete('cascade');
            $table->unsignedInteger('instrument_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persons_to_sessions');
    }
}
