<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShareUserDownloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('share_user_downloads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('share_user_id');
            $table->timestamps();
            $table->foreign('share_user_id')->references('id')->on('share_users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('share_user_downloads');
    }
}
