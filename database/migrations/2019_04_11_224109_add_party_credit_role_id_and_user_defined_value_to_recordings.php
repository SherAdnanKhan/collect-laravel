<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPartyCreditRoleIdAndUserDefinedValueToRecordings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recordings', function (Blueprint $table) {
            $table->unsignedInteger('party_role_id')->nullable()->after('party_id');
            $table->foreign('party_role_id')->references('id')->on('credit_roles')
                ->onDelete('set null');
            $table->string('party_role_user_defined_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recordings', function (Blueprint $table) {
            $table->dropForeign(['party_role_id']);
            $table->dropColumn('party_role_id');
            $table->dropColumn('party_role_user_defined_value');
        });
    }
}
