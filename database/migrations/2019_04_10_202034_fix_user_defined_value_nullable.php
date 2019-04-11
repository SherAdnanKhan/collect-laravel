<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixUserDefinedValueNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->string('credit_role_user_defined_value')->nullable()->change();
            $table->string('instrument_user_defined_value')->nullable()->change();
        });

        Schema::table('recordings', function(Blueprint $table) {
            $table->string('recording_type_user_defined_value')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->string('credit_role_user_defined_value')->nullable(false)->change();
            $table->string('instrument_user_defined_value')->nullable(false)->change();
        });

        Schema::table('recordings', function(Blueprint $table) {
            $table->string('recording_type_user_defined_value')->nullable(false)->change();
        });
    }
}
