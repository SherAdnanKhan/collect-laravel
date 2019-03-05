<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionsToRecordingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions_to_recordings', function (Blueprint $table) {
            $table->unsignedInteger('session_id');
            $table->foreign('session_id')->references('id')->on('sessions')
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
        Schema::dropIfExists('sessions_to_recordings');
    }
}
