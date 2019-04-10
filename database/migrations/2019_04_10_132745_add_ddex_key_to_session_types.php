<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDdexKeyToSessionTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('session_types', function (Blueprint $table) {
            $table->string('ddex_key')->after('name');
            $table->index('ddex_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('session_types', function (Blueprint $table) {
            $table->dropIndex(['ddex_index']);
            $table->string('ddex_index');
        });
    }
}
