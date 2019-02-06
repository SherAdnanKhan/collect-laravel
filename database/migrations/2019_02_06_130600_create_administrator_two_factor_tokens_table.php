<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdministratorTwoFactorTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administrator_two_factor_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('administrator_id');
            $table->foreign('administrator_id')->references('id')->on('administrators')
                ->onDelete('cascade');
            $table->string('token');
            $table->dateTime('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('administrator_two_factor_tokens');
    }
}
