<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRecordingIdToCollaborators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborators', function (Blueprint $table) {
            $table->unsignedInteger('recording_id')->after('project_id')->nullable();
            $table->foreign('recording_id')->references('id')->on('recordings')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborators', function (Blueprint $table) {
            $table->dropForeign(['recording_id']);
            $table->dropColumn('recording_id');
        });
    }
}
