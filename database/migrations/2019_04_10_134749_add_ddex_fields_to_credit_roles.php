<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDdexFieldsToCreditRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_roles', function (Blueprint $table) {
            $table->string('ddex_key')->after('name');
            $table->boolean('user_defined')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credit_roles', function (Blueprint $table) {
            $table->dropColumn('ddex_key');
            $table->dropColumn('user_defined');
        });
    }
}
