<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('session_id');
            $table->string('code')->index();
            $table->timestamps();
            $table->foreign('session_id')->references('id')->on('sessions')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('session_codes');
    }
}
