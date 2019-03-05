<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaboratorPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaborator_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('collaborator_id');
            $table->foreign('collaborator_id')->references('id')->on('collaborators')
                ->onDelete('cascade');
            $table->string('level');
            $table->index('level');
            $table->timestamps();
        });

        Schema::table('collaborators', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collaborator_permissions');

         Schema::table('collaborators', function (Blueprint $table) {
            $table->string('level');
        });
    }
}
