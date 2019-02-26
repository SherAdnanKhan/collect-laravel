<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProjectIdToSongsToRecordings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('songs_to_recordings', function (Blueprint $table) {
            $table->unsignedInteger('project_id');
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
        Schema::table('songs_to_recordings', function (Blueprint $table) {
            $table->dropColumn('project_id');
        });
    }
}
