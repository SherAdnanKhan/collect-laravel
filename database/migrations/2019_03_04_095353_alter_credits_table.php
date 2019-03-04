<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credits', function(Blueprint $table) {
            $table->dropForeign(['song_recording_id']);
            $table->dropColumn('song_recording_id');
            $table->dropColumn('email');
            $table->dropColumn('name');
            $table->unsignedInteger('person_id')->after('id');
            $table->foreign('person_id')->references('id')->on('persons')
                ->onDelete('cascade');
            $table->unsignedInteger('contribution_id')->after('person_id');
            $table->string('contribution_type')->after('contribution_id');
            $table->index(['contribution_id', 'contribution_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credits', function(Blueprint $table) {
            $table->dropForeign(['person_id']);
            $table->dropColumn('person_id');
            $table->dropIndex(['contribution_id', 'contribution_type']);
            $table->dropColumn('contribution_id');
            $table->dropColumn('contribution_type');
            $table->string('name')->after('role');
            $table->unsignedInteger('song_recording_id')->after('id');
            $table->foreign('song_recording_id')->references('id')->on('songs_to_recordings')
                ->onDelete('cascade');
            $table->string('email')->after('name')->nullable();
        });
    }
}
