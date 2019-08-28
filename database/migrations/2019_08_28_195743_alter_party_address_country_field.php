<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPartyAddressCountryField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('party_addresses', function (Blueprint $table) {
            $table->dropColumn('territory_code');
            $table->unsignedInteger('country_id')->after('postal_code')->nullable()->default(1);
            $table->foreign('country_id')->references('id')->on('countries')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('party_addresses', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropColumn('country_id');
            $table->string('territory_code', 255)->after('postal_code');
        });
    }
}
