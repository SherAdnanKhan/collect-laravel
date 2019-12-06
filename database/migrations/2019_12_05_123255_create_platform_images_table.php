<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatformImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('location')->nullable(false)->index();
            $table->string('path')->nullable(false);
            $table->string('caption')->nullable();
            $table->string('alt_text')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('platform_images');
    }
}
