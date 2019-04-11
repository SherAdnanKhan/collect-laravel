<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserDefinedSongTypeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('song_types', function(Blueprint $table) {
            $table->boolean('user_defined');
            $table->index('user_defined');
        });

        Schema::table('songs', function (Blueprint $table) {
            $table->string('song_type_user_defined_value')->nullable()->after('song_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('song_types', function(Blueprint $table) {
            $table->dropIndex(['user_defined']);
            $table->dropColumn('user_defined');
        });

        Schema::table('songs', function (Blueprint $table) {
            $table->dropColumn('song_type_user_defined_value');
        });
    }
}
