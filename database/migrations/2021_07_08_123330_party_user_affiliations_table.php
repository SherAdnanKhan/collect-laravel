<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PartyUserAffiliationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('party_user_affiliations', function (Blueprint $table) {
            $table->unsignedInteger('party_id');
            $table->foreign('party_id')->references('id')->on('parties')
                ->onDelete('cascade');
            $table->unsignedInteger('user_affiliation_id');
            $table->foreign('user_affiliation_id')->references('id')->on('user_affiliations')
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
        Schema::dropIfExists('party_user_affiliations');
    }
}
