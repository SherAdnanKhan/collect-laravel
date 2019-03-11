<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditsToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits_to_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('credit_id')->after('id');
            $table->foreign('credit_id')->references('id')->on('credits')
                ->onDelete('cascade');
            $table->unsignedInteger('project_id')->after('id');
            $table->foreign('project_id')->references('id')->on('projects')
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
        Schema::dropIfExists('credits_to_projects');
    }
}
