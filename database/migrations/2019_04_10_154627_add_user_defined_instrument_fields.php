<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserDefinedInstrumentFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instruments', function(Blueprint $table) {
            $table->boolean('user_defined');
            $table->index('user_defined');

            $table->dropColumn('category');
        });

        Schema::table('credits', function (Blueprint $table) {
            $table->string('instrument_user_defined_value')->after('instrument_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instruments', function(Blueprint $table) {
            $table->dropIndex(['user_defined']);
            $table->dropColumn('user_defined');

            $table->string('category');
        });

        Schema::table('credits', function (Blueprint $table) {
            $table->dropColumn('instrument_user_defined_value');
        });
    }
}
