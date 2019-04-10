<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDdexKeyColumnToSongTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('song_types', function (Blueprint $table) {
            $table->string('ddex_key')->after('name');
            $table->unique('ddex_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('song_types', function (Blueprint $table) {
            $table->dropColumn('ddex_key');
        });
    }
}
