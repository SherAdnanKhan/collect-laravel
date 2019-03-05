<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSongsToRecordingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('songs_to_recordings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('song_id');
            $table->foreign('song_id')->references('id')->on('songs')
                ->onDelete('cascade');
            $table->unsignedInteger('recording_id');
            $table->foreign('recording_id')->references('id')->on('recordings')
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
        Schema::dropIfExists('songs_to_recordings');
    }
}
