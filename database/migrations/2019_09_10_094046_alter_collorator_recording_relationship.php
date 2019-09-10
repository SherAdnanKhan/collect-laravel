<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColloratorRecordingRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaborators_to_recordings', function(Blueprint $table) {
            $table->unsignedInteger('collaborator_id');
            $table->foreign('collaborator_id')->references('id')->on('collaborators')
                ->onDelete('cascade');

            $table->unsignedInteger('recording_id');
            $table->foreign('recording_id')->references('id')->on('recordings')
                ->onDelete('cascade');
        });

        // Migrate relationship over to new table.
        DB::statement('insert into collaborators_to_recordings(collaborator_id, recording_id) select id as collaborator_id, recording_id from collaborators where recording_id is not null');

        Schema::table('collaborators', function(Blueprint $table) {
            $table->dropForeign(['recording_id']);
            $table->dropColumn('recording_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborators', function(Blueprint $table) {
            $table->unsignedInteger('recording_id')->after('project_id')->nullable();
            $table->foreign('recording_id')->references('id')->on('recordings')
                ->onDelete('set null');
        });

        // Migrate relationship back to old table.
        DB::statement('update collaborators inner join collaborators_to_recordings on collaborators.id = collaborators_to_recordings.collaborator_id set collaborators.recording_id = collaborators_to_recordings.recording_id;');

        Schema::dropIfExists('collaborators_to_recordings');
    }
}
