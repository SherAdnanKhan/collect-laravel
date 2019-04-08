<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixRequiredFieldsOnSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sessions', function(Blueprint $table) {
            $table->dropForeign(['venue_id']);
            $table->dropForeign(['session_type_id']);
        });

        Schema::table('sessions', function(Blueprint $table) {

            $table->unsignedInteger('venue_id')->nullable(false)->change();
            $table->unsignedInteger('session_type_id')->nullable(false)->change();

            $table->foreign('venue_id')->references('id')->on('venues')
                ->onDelete('cascade');
            $table->foreign('session_type_id')->references('id')->on('session_types')
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
        Schema::table('sessions', function(Blueprint $table) {
            $table->dropForeign(['venue_id']);
            $table->dropForeign(['session_type_id']);
        });

        Schema::table('sessions', function(Blueprint $table) {
            $table->unsignedInteger('venue_id')->nullable(true)->change();
            $table->unsignedInteger('session_type_id')->nullable(true)->change();

            $table->foreign('venue_id')->references('id')->on('venues')
                ->onDelete('set null');
            $table->foreign('session_type_id')->references('id')->on('session_types')
                ->onDelete('set null');
        });
    }
}
