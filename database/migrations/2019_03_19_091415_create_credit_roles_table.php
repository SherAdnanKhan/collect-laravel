<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('name');
            $table->index('type');
        });

        Schema::table('credits', function(Blueprint $table) {
            $table->dropColumn('role');
            $table->unsignedInteger('credit_role_id')->nullable()->after('contribution_type');
            $table->foreign('credit_role_id')->references('id')->on('credit_roles')
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
        Schema::table('credits', function(Blueprint $table) {
            $table->string('role')->nullable()->after('contribution_type');
            $table->dropForeign(['credit_role_id']);
            $table->dropColumn('credit_role_id');
        });

        Schema::dropIfExists('credit_roles');
    }
}
