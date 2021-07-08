<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAffiliationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_affiliations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade');
            $table->unsignedInteger('affiliation_id');
            $table->foreign('affiliation_id')->references('id')->on('affiliations')
                ->onDelete('cascade');
            $table->string('number', 50)->nullable();
            $table->enum('status', ['unverified', 'pending', 'verified'])->default('unverified');
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
        Schema::table('user_affiliations', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['affiliation_id']);
        });

        Schema::dropIfExists('user_affiliations');
    }
}
