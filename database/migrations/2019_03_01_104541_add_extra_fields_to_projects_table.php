<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraFieldsToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('image');
            $table->string('label');
            $table->string('artist');
            $table->string('number')->nullable();
            $table->unique('number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('label');
            $table->dropColumn('artist');
            $table->dropUnique(['number']);
            $table->dropColumn('number');
        });
    }
}
