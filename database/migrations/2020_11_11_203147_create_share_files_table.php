<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShareFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('share_files', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('share_id');
            $table->unsignedInteger('file_id');
            $table->unsignedInteger('folder_id')->nullable();
            $table->timestamps();
            $table->foreign('share_id')->references('id')->on('shares')->onDelete('CASCADE');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('share_files');
    }
}
