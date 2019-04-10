<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordingTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recording_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('ddex_key');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('ddex_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recording_types');
    }
}
