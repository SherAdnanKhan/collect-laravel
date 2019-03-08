<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('songs', function(Blueprint $table) {
            $table->dropColumn('iswc');
            $table->string('isrc')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('songs', function(Blueprint $table) {
            $table->dropUnique(['isrc']);
            $table->dropColumn('isrc');
            $table->string('iswc')->nullable();
        });
    }
}
