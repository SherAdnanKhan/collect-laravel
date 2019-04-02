<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserTwoFactorTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('user_two_factor_tokens');

        Schema::table('users', function(Blueprint $table) {
            $table->string('phone')->nullable()->after('password');
            $table->boolean('two_factor_enabled')->nullable()->after('remember_token');

            $table->index('phone');
            $table->index('two_factor_enabled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('user_two_factor_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade');
            $table->string('token');
            $table->dateTime('expires_at');
        });

        Schema::table('users', function(Blueprint $table) {
            $table->dropIndex(['phone']);
            $table->dropIndex(['two_factor_enabled']);

            $table->dropColumn('phone');
            $table->dropColumn('two_factor_enabled');
        });
    }
}
